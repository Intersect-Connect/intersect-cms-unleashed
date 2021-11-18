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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }


    /**
     * @Route("/api/discord/register", name="api.discord.register")
     */
    public function discordRegister(Request $request, Api $api, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $username = $request->request->get('username');
            $password = $request->request->get('plainPassword');
            $email = $request->request->get('email');

            if (
                isset($username) && !empty($username) &&
                isset($password) && !empty($password) &&
                isset($email) && !empty($email)
            ) {
                $user_infos = $api->getUser($username);

                // return new JsonResponse([$user_infos]);


                if (isset($user_infos['Message']) && $user_infos['Message'] == "No user with name '" . $username . "'.") {
                    $userData = array(
                        'username' => $username,
                        'password' => hash('sha256', $password),
                        'email' => $email
                    );

                    $register = $api->registerUser($userData);

                    if (isset($register['Username']) && $register['Username'] == $username) {
                        $user_infos = $api->getUser($username);

                        $user = new User();

                        // encode the plain password
                        $user->setPassword(
                            $passwordEncoder->encodePassword(
                                $user,
                                $password
                            )
                        );
                        $user->setEmail($email);

                        $user->setUsername($username);
                        $user->setId($user_infos['Id']);
                        $user->setPoints(0);

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();

                        return new JsonResponse(['success' => true, 'message' => 'Inscription ok.']);
                    } else {
                        return new JsonResponse(['success' => false, 'message' => 'Debug1']);
                    }
                } else {
                    return new JsonResponse(['success' => false, 'message' => 'Debug2']);
                }
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Champ manquant']);
            }
        } else {
            return new JsonResponse(['success' => false, 'message' => 'Bad Request']);
        }
    }
}
