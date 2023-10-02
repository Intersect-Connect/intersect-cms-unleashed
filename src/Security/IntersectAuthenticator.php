<?php

namespace App\Security;

use App\Entity\User;
use App\Settings\Api;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class IntersectAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Security $security,
        private EntityManagerInterface $entityManager,
        private Api $api
    ) {
    }

    public function supports(Request $request): ?bool
    {
        // Déterminez si cet authenticator doit être utilisé pour cette requête
        // Par exemple, vérifiez si la route correspond à la connexion
        // et si la méthode HTTP est POST
        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Récupérez les informations d'identification de la requête (par exemple, nom d'utilisateur et mot de passe)
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
        ];

        // Créez un badge UserBadge avec l'identifiant de l'utilisateur
        $userBadge = new UserBadge($credentials['username'], function ($userIdentifier) use ($credentials) {
            // Recherchez l'utilisateur dans la base de données par son nom d'utilisateur
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $userIdentifier]);

            $userData = array(
                'username' => $credentials['username'],
                'password' => hash('sha256', $credentials['password'])
            );

            if (!$user) {
                // L'utilisateur n'a pas été trouvé, cela peut être un nouvel utilisateur
                // Vérifiez s'il existe dans votre API (remplacez cela par votre propre logique)
                if ($this->api->passwordVerify($userData, $userIdentifier)) {
                    // Utilisateur trouvé dans l'API, créez un nouvel utilisateur
                    $user_infos = $this->api->APIcall_GET($this->api->getServer(), $this->api->getToken(), '/api/v1/users/' . $credentials['username']);

                    $newUser = new User();
                    $newUser->setId($user_infos['Id']);
                    $newUser->setUsername($credentials['username']);
                    $newUser->setPassword(password_hash($credentials['password'], PASSWORD_ARGON2I));
                    $newUser->setPasswordToken(null);
                    $newUser->setPoints(0);
                    $newUser->setAdmin(0);
                    $newUser->setRoles([]);
                    $newUser->setEmail($user_infos['Email']);
                    $this->entityManager->persist($newUser);
                    $this->entityManager->flush();
                } else {
                    // L'utilisateur n'existe ni dans la base de données ni dans l'API
                    throw new CustomUserMessageAuthenticationException('Username could not be found.');
                }
            }

            return $user;
        });

        // Créez un badge PasswordCredentials avec le mot de passe
        $passwordBadge = new PasswordCredentials($credentials['password']);

        // Créez un objet Passport avec les badges
        $passport = new Passport($userBadge, $passwordBadge);

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Réagissez à une authentification réussie (redirection, etc.)
        // Vous pouvez personnaliser la logique ici
        $homeUrl = $this->urlGenerator->generate('home');
        return new RedirectResponse($homeUrl);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Réagissez à une authentification échouée (redirection, message d'erreur, etc.)
        // Vous pouvez personnaliser la logique ici
        $loginUrl = $this->urlGenerator->generate('app_login');
        return new RedirectResponse($loginUrl);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        // Réagissez à une demande non authentifiée (redirection vers la page de connexion, etc.)
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    protected function getLoginUrl(Request $request): string
    {
        // URL de redirection en cas d'échec d'authentification
        return $this->urlGenerator->generate('app_login');
    }
}
