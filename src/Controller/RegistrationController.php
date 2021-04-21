<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use App\Settings\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Api $api, LoginAuthenticator $login, GuardAuthenticatorHandler $guard): Response
    {
        $serveur_statut = $api->ServeurStatut();

        if ($serveur_statut['success']) {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user_infos = $api->getUser($form->get('username')->getData());

                if (isset($user_infos['Message']) && $user_infos['Message'] == "No user with name '" . $form->get('username')->getData() . "'.") {
                    $userData = array(
                        'username' => $form->get('username')->getData(),
                        'password' => hash('sha256', $form->get('plainPassword')->getData()),
                        'email' => $form->get('email')->getData()
                    );
                    $register = $api->registerUser($userData);

                    if (isset($register['Username']) && $register['Username'] == $form->get('username')->getData()) {
                        $user_infos = $api->getUser($form->get('username')->getData());

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
                        $entityManager->persist($user);
                        $entityManager->flush();
                        return $guard->authenticateUserAndHandleSuccess($user, $request, $login, 'main');
                    }
                }

                // do anything else you need here, like send an email

                return $this->redirectToRoute('home');
            }

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        } else {
            return $this->render('registration/register.html.twig', [
                'serveur_statut' => false,
            ]);
        }
    }
}
