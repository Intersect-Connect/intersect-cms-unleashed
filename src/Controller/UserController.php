<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Settings\Api;
use App\Entity\CmsPointsHistory;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Repository\CmsShopRepository;
use App\Security\IntersectAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use Symfony\Contracts\Cache\CacheInterface;
use App\Repository\CmsShopHistoryRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CmsPointsHistoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings,
        protected Api $api,
        protected CacheInterface $cache,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        protected UserRepository $userRepo,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    #[Route(path: '/account', name: 'account')]
    public function index(Request $request, UserRepository $userRepo): Response
    {
        $data = [
            'page' => 0,
            'count' => 10
        ];
        $user = $this->userRepo->find($this->getUser());


        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $newEmail = $request->request->get('newEmail');
            $emailConfirm = $request->request->get('emailConfirm');
            $emailPassword = $request->request->get('emailPassword');

            $password = $request->request->get('password');
            $newPassword = $request->request->get('newPassword');
            $passwordConfirm = $request->request->get('passwordConfirm');
            $user = $this->userRepo->find($this->getUser());

            if (isset($email) && !empty($email) && isset($emailConfirm) && !empty($emailConfirm)) {
                if ($email == $user->getEmail()) {

                    if ($user) {
                        if ($newEmail === $emailConfirm && password_verify($emailPassword, $user->getPassword())) {
                            $data = [
                                'new' => $emailConfirm,
                                'authorization' => hash("sha256", $emailPassword)
                            ];

                            if ($this->api->changeEmailAccount($data, $user->getId())) {
                                $user->setEmail($emailConfirm);
                                $this->entityManager->persist($user);
                                $this->entityManager->flush();
                                $this->addFlash('success', $this->translator->trans('Votre adresse e-mail a été modifier'));
                                return $this->redirectToRoute('account');
                            } else {
                                $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                                return $this->redirectToRoute('account');
                            }
                        }
                    }
                }
            }

            if (isset($password) && !empty($password) && isset($newPassword) && !empty($newPassword)) {
                if (password_verify($password, $this->getUser()->getPassword())) {
                    $user = $this->userRepo->find($this->getUser());

                    if ($user) {
                        if ($newPassword === $passwordConfirm) {

                            $data = [
                                'new' => hash("sha256", $passwordConfirm),
                            ];


                            if ($this->api->changePasswordAccount($data, $user->getId())) {
                                $user->setPassword(password_hash($passwordConfirm, PASSWORD_ARGON2ID));
                                $this->entityManager->persist($user);
                                $this->entityManager->flush();
                                $this->addFlash('success', $this->translator->trans('Votre mot de passe a bien été modifié.'));
                                return $this->redirectToRoute('account');
                            } else {
                                $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                                return $this->redirectToRoute('account');
                            }
                        }
                    }
                }
            }
        }

        $classes_array = $this->api->getGameClass($data);
        $classes = [];

        if (isset($classes_array['entries'])) {
            $classes = $classes_array['entries'];
        }

        $players_array = $this->api->getCharacters($user->getId());
        $players = [];

        if (!isset($players_array['error'])) {
            $players = $players_array;
        }


        return $this->render('Application/' . $this->settings->get('theme') . '/user/index.html.twig', [
            'classes' => $classes,
            'players' => $players
        ]);
    }


    #[Route(path: '/account/credits', name: 'account.credit.reload')]
    public function credit(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');

            if (!empty($code)) {
                $dedipass = file_get_contents('http://api.dedipass.com/v1/pay/?public_key=' . $this->api->getDedipassPublic() . '&private_key=' . $this->api->getDedipassPrivate() . '&code=' . $code);
                $dedipass = json_decode($dedipass);

                if ($dedipass->status == 'success') {
                    $virtual_currency = $dedipass->virtual_currency;
                    $user = $this->userRepo->find($request->request->get('custom'));

                    if ($user) {
                        $user->setPoints($user->getPoints() + $virtual_currency);
                        $this->entityManager->persist($user);
                        $this->entityManager->flush();

                        $pointHistorique = new CmsPointsHistory();
                        $pointHistorique->setDate(new DateTime());
                        $pointHistorique->setUserId($user->getId());
                        $pointHistorique->setCode($code);
                        $pointHistorique->setPointsAmount($virtual_currency);
                        $this->entityManager->persist($pointHistorique);
                        $this->entityManager->flush();



                        $this->addFlash('success', $this->translator->trans('Votre compte a été rechargé en points boutique'));

                        $this->autoLoginUser($user);
                        return $this->redirectToRoute('account');
                    }
                }
            }
        }
        return $this->render('Application/' . $this->settings->get('theme') . '/user/credit.html.twig', [
            'dedipass' => $this->api->getDedipassPublic()
        ]);
    }

    #[Route(path: '/account/history', name: 'account.history', requirements: ['_locale' => 'en|fr'])]
    public function history(Request $request, CmsShopHistoryRepository $shopHistory, CmsShopRepository $cmsShopRepo, CmsPointsHistoryRepository $pointsRepo): Response
    {
        $user = $this->userRepo->find($this->getUser());
        $shop_history = $shopHistory->findBy(['userId' => $user->getId()]);
        $point_history = $pointsRepo->findBy(['userId' => $user->getId()]);
        $history = [];

        foreach ($shop_history as $shop_history) {
            $shop_item = $cmsShopRepo->find($shop_history->getShopId());
            if ($shop_item) {
                $history[] = [
                    'name' => $shop_item->getName(),
                    'date' => $shop_history->getDate()->format('d/m/Y à h:i:s'),
                    'type' => 'item_shop',
                    'price' => $shop_item->getPrice(),
                    'quantity' => $shop_item->getQuantity()
                ];
            }
        }

        foreach ($point_history as $point_history) {
            $history[] = [
                'name' => $this->translator->trans('Achat points boutique VIP'),
                'date' => $point_history->getDate()->format('d/m/Y à h:i:s'),
                'type' => 'points_vip',
                'code' => $point_history->getCode(),
                'quantity' => $point_history->getPointsAmount()
            ];
        }



        return $this->render('Application/' . $this->settings->get('theme') . '/user/history.html.twig', [
            'history' => $history
        ]);
    }

    private function autoLoginUser(User $user): void
    {
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }
}
