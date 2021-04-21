<?php

namespace App\Controller;

use App\Entity\CmsPointsHistory;
use App\Repository\CmsPointsHistoryRepository;
use App\Repository\CmsShopHistoryRepository;
use App\Repository\CmsShopRepository;
use App\Repository\UserRepository;
use App\Settings\Api;
use App\Settings\CmsSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Api $api, Request $request, UserRepository $userRepo, TranslatorInterface $translator): Response
    {
        $data = [
            'page' => 0,
            'count' => 10
        ];


        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $newEmail = $request->request->get('newEmail');
            $emailConfirm = $request->request->get('emailConfirm');
            $emailPassword = $request->request->get('emailPassword');

            $password = $request->request->get('password');
            $newPassword = $request->request->get('newPassword');
            $passwordConfirm = $request->request->get('passwordConfirm');

            if (isset($email) && !empty($email) && isset($emailConfirm) && !empty($emailConfirm)) {
                if ($email == $this->getUser()->getEmail()) {
                    $user = $userRepo->find($this->getUser());

                    if ($user) {
                        if ($newEmail === $emailConfirm && password_verify($emailPassword, $user->getPassword())) {
                            $data = [
                                'new' => $emailConfirm,
                                'authorization' => hash("sha256", $emailPassword)
                            ];

                            if ($api->changeEmailAccount($data, $this->getUser()->getId())) {
                                $user->setEmail($emailConfirm);
                                $entityManager = $this->getDoctrine()->getManager();
                                $entityManager->persist($user);
                                $entityManager->flush();
                                $this->addFlash('success', $translator->trans('Votre adresse e-mail a été modifier'));
                                return $this->redirectToRoute('account');
                            } else {
                                $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                                return $this->redirectToRoute('account');
                            }
                        }
                    }
                }
            }

            if (isset($password) && !empty($password) && isset($newPassword) && !empty($newPassword)) {
                if (password_verify($password, $this->getUser()->getPassword())) {
                    $user = $userRepo->find($this->getUser());

                    if ($user) {
                        if ($newPassword === $passwordConfirm) {

                            $data = [
                                'new' => hash("sha256", $passwordConfirm),
                            ];


                            if ($api->changePasswordAccount($data, $this->getUser()->getId())) {
                                $user->setPassword(password_hash($passwordConfirm, PASSWORD_ARGON2ID));
                                $entityManager = $this->getDoctrine()->getManager();
                                $entityManager->persist($user);
                                $entityManager->flush();
                                $this->addFlash('success', $translator->trans('Votre mot de passe a bien été modifié.'));
                                return $this->redirectToRoute('account');
                            } else {
                                $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                                return $this->redirectToRoute('account');
                            }
                        }
                    }
                }
            }
        }

        $classes = $api->getGameClass($data);
        $players = $api->getCharacters($this->getUser()->getId());


        return $this->render('user/index.html.twig', [
            'classes' => $classes['entries'],
            'players' => $players
        ]);
    }


    /**
     * @Route("/account/credits", name="account.credit.reload")
     */
    public function credit(Api $api, Request $request, CmsSettings $settings, UserRepository $userRepo, TranslatorInterface $translator): Response
    {
        if ($request->isMethod('post')) {
            $code = $request->request->get('code');

            if (!empty($code)) {
                $dedipass = file_get_contents('http://api.dedipass.com/v1/pay/?public_key=' . $api->getDedipassPublic() . '&private_key=' . $api->getDedipassPrivate() . '&code=' . $code);
                $dedipass = json_decode($dedipass);

                if ($dedipass->status == 'success') {
                    $virtual_currency = $dedipass->virtual_currency;
                    $user = $userRepo->find($this->getUser());

                    if ($user) {
                        $user->setPoints($user->getPoints() + $virtual_currency);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                        $this->addFlash('success', $translator->trans('Votre compte a été rechargé en points boutique'));
                        return $this->redirectToRoute('account');
                    }
                }
            }
        }

        return $this->render('user/credit.html.twig', [
            'dedipass' => $api->getDedipassPublic()
        ]);
    }

    /**
     * @Route("/account/history", name="account.history")
     */
    public function history(Api $api, Request $request, UserRepository $userRepo, CmsShopHistoryRepository $shopHistory, TranslatorInterface $translator, CmsShopRepository $cmsShopRepo, CmsPointsHistoryRepository $pointsRepo): Response
    {

        $shop_history = $shopHistory->findBy(['userId' => $this->getUser()->getId()]);
        $point_history = $pointsRepo->findBy(['userId' => $this->getUser()->getId()]);

        $history = [];

        foreach ($shop_history as $shop_history) {
            $shop_item = $cmsShopRepo->find($shop_history->getShopId());
            $history[] = [
                'name' => $shop_item->getName(),
                'date' => $shop_history->getDate()->format('d/m/Y à h:i:s'),
                'type' => 'item_shop',
                'price' => $shop_item->getPrice(),
                'quantity' => $shop_item->getQuantity()
            ];
        }

        foreach ($point_history as $point_history) {
            $history[] = [
                'name' => $translator->trans('Achat points boutique VIP'),
                'date' => $point_history->getDate()->format('d/m/Y à h:i:s'),
                'type' => 'points_vip',
                'code' => $point_history->getCode(),
                'quantity' => $point_history->getPointsAmount()
            ];
        }



        return $this->render('user/history.html.twig', [
            'history' => $history
        ]);
    }
}