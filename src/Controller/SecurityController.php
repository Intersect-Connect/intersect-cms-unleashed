<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Settings\Api;
use App\Settings\CmsSettings;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login",  requirements={"_locale": "en|fr"})
     */
    public function login(AuthenticationUtils $authenticationUtils, CmsSettings $settings): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home.index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render($settings->get('theme') . '/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/password-reset", name="passwordResetRequest", requirements={"_locale": "en|fr"})
     */
    public function passwordResetRequest(Request $request, UserRepository $userRepo, TranslatorInterface $translator, MailerInterface $mailer, CmsSettings $settings)
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $email = $request->request->get('email');

            if (
                isset($username) && !empty($username) &&
                isset($email) && !empty($email)
            ) {
                $user = $userRepo->findOneBy(['username' => $username, 'email' => $email]);

                if ($user) {
                    $token = bin2hex(random_bytes(12));
                    $user->setPasswordToken($token);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                     $url = $this->generateUrl('passwordResetRequest.new',['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                    $email = (new TemplatedEmail())
                        ->from(new Address("hello@example.com", "Your Name"))
                        ->to($user->getEmail())
                        ->subject($translator->trans('Demande de nouveau mot de passe'))
                        ->htmlTemplate($settings->get('theme') . '/emails/password-reset.html.twig')
                        ->context([
                            'username' => $user->getUsername(),
                            'url' => $url,
                        ]);
                    $mailer->send($email);

                    $this->addFlash('success', $translator->trans('Votre demande de mot de passe a bien été envoyée.'));
                    return $this->redirectToRoute('passwordResetRequest');
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('passwordResetRequest');
                }
            }
        }

        return $this->render($settings->get('theme') . '/security/password-reset.html.twig', []);
    }

    /**
     * @Route("/password-reset/new/{token}", name="passwordResetRequest.new", requirements={"_locale": "en|fr"})
     */
    public function passwordReset(Request $request, UserRepository $userRepo, TranslatorInterface $translator, MailerInterface $mailer, $token, Api $api, CmsSettings $settings)
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $passwordConfirm = $request->request->get('passwordConfirm');

            if (
                isset($username) && !empty($username) &&
                isset($email) && !empty($email) &&
                isset($password) && !empty($password) &&
                isset($passwordConfirm) && !empty($passwordConfirm)
            ) {
                $user = $userRepo->findOneBy(['username' => $username, 'email' => $email]);

                if ($user) {
                    if ($password === $passwordConfirm) {
                        $data = ['new' => hash("sha256", $passwordConfirm)];
                        if ($api->changePasswordAccount($data, $user->getId())) {
                            $user->setPasswordToken(null);
                            $user->setPassword(password_hash($passwordConfirm, PASSWORD_ARGON2ID));
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($user);
                            $entityManager->flush();
                            $this->addFlash('success', $translator->trans('Votre mot de passe a bien été modifié.'));
                            return $this->redirectToRoute('app_login');
                        } else {
                            $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                            return $this->redirectToRoute('passwordResetRequest.new', ['token' => $token]);
                        }
                    } else {
                        $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                        return $this->redirectToRoute('passwordResetRequest.new', ['token' => $token]);
                    }
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('passwordResetRequest.new', ['token' => $token]);
                }
            }
        }

        $users = $userRepo->findBy(['passwordToken' => $token]);
        if ($users) {
            return $this->render($settings->get('theme') . '/security/password-reset-new.html.twig', [
                'confirm' => true
            ]);
        } else {
            return $this->render($settings->get('theme') . '/security/password-reset-new.html.twig', [
                'confirm' => false
            ]);
        }
    }

    /**
     * @Route("/login/game/{token}", name="login.game")
     */
    public function gameLoginTest(Request $request, UserRepository $userRepo, TranslatorInterface $translator, MailerInterface $mailer, $token, Api $api, LoginAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $character =  $api->getCharacter($token);

        if ($character) {
            $user = $userRepo->findOneBy(['id' => $character['UserId']]);
            if ($user) {
                $guard->authenticateUserAndHandleSuccess($user, $request, $login, 'main');
                return $this->redirectToRoute('home');
            }
        }
    }
}
