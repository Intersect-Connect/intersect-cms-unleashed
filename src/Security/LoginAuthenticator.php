<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Security;

use App\Entity\User;
use App\Settings\Api;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginAuthenticator
{
    // use TargetPathTrait;

    // public const LOGIN_ROUTE = 'app_login';

    // private $username;

    // public function __construct(
    //     protected EntityManagerInterface $entityManager,
    //     protected UrlGeneratorInterface $urlGenerator,
    //     protected CsrfTokenManagerInterface $csrfTokenManager,
    //     protected Api $api
    // ) {}

    // public function authenticate(Request $request): Passport
    // {
    //     $username = $request->request->get('username', '');
    //     $password = $request->request->get('password', '');
    //     $csrfToken = $request->request->get('_csrf_token');
    //     $this->username = $username;


    //     return new Passport(
    //         new UserBadge($username, function ($userIdentifier) use ($username, $password) {

    //             $userData = array(
    //                 'username' => $this->username,
    //                 'password' => hash('sha256', $password)
    //             );
    //             $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $userIdentifier]);

    //             if (!$user) {

    //                 if ($this->api->passwordVerify($userData, $userIdentifier)) {

    //                     $user_infos = $this->api->APIcall_GET($this->api->getServer(), $this->api->getToken(), '/api/v1/users/' . $username);

    //                     $newUser = new User();
    //                     $newUser->setId($user_infos['Id']);
    //                     $newUser->setUsername($this->username);
    //                     $newUser->setPassword(password_hash($password, PASSWORD_ARGON2I));
    //                     $newUser->setPasswordToken(null);
    //                     $newUser->setPoints(0);
    //                     $newUser->setAdmin(0);
    //                     $newUser->setRoles([]);
    //                     $newUser->setEmail($user_infos['Email']);
    //                     $this->entityManager->persist($newUser);
    //                     $this->entityManager->flush();
    //                 }
    //             }

    //             $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);


    //             if (!$user) {
    //                 // fail authentication with a custom error
    //                 throw new CustomUserMessageAuthenticationException('Username could not be found.');
    //             }

    //             return $user;
    //         }),
    //         new PasswordCredentials($password),
    //         [
    //             new CsrfTokenBadge('authenticate', $csrfToken),
    //         ]
    //     );
    // }

    // /**
    //  * Used to upgrade (rehash) the user's password automatically over time.
    //  */
    // public function getPassword($credentials): ?string
    // {
    //     return $credentials['password'];
    // }


    // public function supports(Request $request): bool
    // {
    //     return self::LOGIN_ROUTE === $request->attributes->get('_route')
    //         && $request->isMethod('POST');
    // }
}
