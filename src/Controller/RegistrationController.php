<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller;

use App\Entity\User;
use App\Settings\Api;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use App\Settings\Settings as CmsSettings;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        protected SettingsCmsSettings $settings,
        protected Api $api,
        protected UserRepository $userRepo,
        protected GuardAuthenticatorHandler $guard,
        protected LoginAuthenticator $login,
        protected UserPasswordEncoderInterface $passwordEncoder,
        protected EntityManagerInterface $entityManager
    ){

    }

    #[Route(path: '/register', name: 'app_register', requirements: ['_locale' => 'en|fr'])]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Api $api, LoginAuthenticator $login, GuardAuthenticatorHandler $guard, CmsSettings $settings, UserRepository $userRepo): Response
    {
        $serveur_statut = $this->api->ServeurStatut();

        if ($serveur_statut['success']) {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user_infos = $this->api->getUser($form->get('username')->getData());
                $userEmailExist = $userRepo->findOneBy(['email' => $form->get('email')->getData()]);


                if (isset($user_infos['Message']) && $user_infos['Message'] == "No user with name '" . $form->get('username')->getData() . "'." && !$userEmailExist) {
                    
                    $userData = array(
                        'username' => $form->get('username')->getData(),
                        'password' => hash('sha256', $form->get('plainPassword')->getData()),
                        'email' => $form->get('email')->getData()
                    );

                    $register = $this->api->registerUser($userData);

                    if (isset($register['Username']) && $register['Username'] == $form->get('username')->getData()) {
                        $user_infos = $this->api->getUser($form->get('username')->getData());

                        // encode the plain password
                        $user->setPassword(
                            $passwordEncoder->encodePassword(
                                $user,
                                $form->get('plainPassword')->getData()
                            )
                        );
                        $user->setId($user_infos['Id']);
                        $user->setPoints(0);

                        $entityManager = $this->getDoctrine()->getManager();
                        $this->entityManager->persist($user);
                        $this->entityManager->flush();

                        return $guard->authenticateUserAndHandleSuccess($user, $request, $login, 'main');
                    }
                }

                // do anything else you need here, like send an email

                return $this->redirectToRoute('home');
            }

            return $this->render($this->settings->get('theme') . '/registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        } else {
            return $this->render($this->settings->get('theme') . '/registration/register.html.twig', [
                'serveur_statut' => false,
            ]);
        }
    }
}
