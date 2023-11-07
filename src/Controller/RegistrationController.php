<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller;

use App\Entity\User;
use App\Settings\Api;
use Doctrine\ORM\EntityManager;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Security\IntersectAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings,
        protected Api $api,
        protected UserRepository $userRepo,
        protected UserAuthenticatorInterface $guard,
        protected IntersectAuthenticator $login,
        protected UserPasswordHasherInterface $UserPasswordHasherInterface,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        private Security $security,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    #[Route(path: '/register', name: 'app_register', requirements: ['_locale' => 'en|fr'])]
    public function register(Request $request): Response
    {
        $serveur_statut = $this->api->ServeurStatut();

        if (!$serveur_statut['success']) {
            return $this->render('Application/' . $this->settings->get('theme') . '/registration/register.html.twig', [
                'serveur_statut' => false,
            ]);
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $email = $form->get('email')->getData();

            if ($this->isUserUnique($username, $email)) {
                $userData = [
                    'username' => $username,
                    'password' => hash('sha256', $form->get('plainPassword')->getData()),
                    'email' => $email,
                ];

                $register = $this->api->registerUser($userData);

                if (isset($register['Username']) && $register['Username'] == $username) {
                    $user_infos = $this->api->getUser($username);

                    // Encode the plain password
                    $user->setPassword(
                        $this->UserPasswordHasherInterface->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                    $user->setId($user_infos['Id']);
                    $user->setPoints(0);

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    $this->autoLoginUser($user);

                    return $this->redirectToRoute('home');
                } else {
                    $this->addFlash('error', $this->translator->trans('Vérifiez vos informations, code d\'erreur : R001'));
                }
            } else {
                $this->addFlash('error', $this->translator->trans('Vérifiez vos informations, code d\'erreur : R002'));
            }
        } else {
            if ($form->isSubmitted() && !$form->isValid()) {
                $this->addFlash('error', $this->translator->trans('Vérifiez vos informations, code d\'erreur : R003'));
            }
        }

        return $this->render('Application/' . $this->settings->get('theme') . '/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function isUserUnique(string $username, string $email): bool
    {
        $userInfos = $this->api->getUser($username);
        $userEmailExist = $this->userRepo->findOneBy(['email' => $email]);

        return isset($userInfos['Message']) && $userInfos['Message'] == "Not Found" && !$userEmailExist;
    }

    private function autoLoginUser(User $user): void
    {
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }
}
