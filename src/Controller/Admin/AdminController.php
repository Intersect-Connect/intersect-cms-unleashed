<?php

namespace App\Controller\Admin;

use App\Repository\CmsNewsRepository;
use App\Repository\CmsSettingsRepository;
use App\Repository\CmsShopRepository;
use App\Settings\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Api $api, CmsShopRepository $shop, CmsNewsRepository $news): Response
    {
        return $this->render('admin/index.html.twig', [
            'total_users' => $api->getAllUsers(0)['Total'],
            'total_players' => $api->getAllPlayers(0)['Total'],
            'total_shop' => count($shop->findAll()),
            'total_news' => count($news->findAll())
        ]);
    }


    /**
     * @Route("admin/settings", name="admin.settings")
     */
    public function settings(Api $api, CmsSettingsRepository $settings, Request $request, TranslatorInterface $translator): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $api_password = $request->request->get('api_password');
            $api_server = $request->request->get('api_server');
            $api_token = $request->request->get('api_token');
            $api_username = $request->request->get('api_username');
            $credit_dedipass_private_key = $request->request->get('credit_dedipass_private_key');
            $credit_dedipass_public_key = $request->request->get('credit_dedipass_public_key');
            $game_title = $request->request->get('game_title');
            $seo_description = $request->request->get('seo_description');
            $use_nav_community = $request->request->get('use_nav_community');
            $use_right_community_button = $request->request->get('use_right_community_button');
            $use_wiki = $request->request->get('use_wiki');
            $facebook_link = $request->request->get('facebook_link');
            $twitter_link = $request->request->get('twitter_link');
            $youtube_link = $request->request->get('youtube_link');
            $instagram_link = $request->request->get('instagram_link');
            $discord_link = $request->request->get('discord_link');


            if (isset($api_password) && !empty($api_password)) {
                $param = $settings->findOneBy(['setting' => 'api_password']);
                $param->setDefaultValue($api_password);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($api_server) && !empty($api_server)) {
                $param = $settings->findOneBy(['setting' => 'api_server']);
                $param->setDefaultValue($api_server);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($api_token) && !empty($api_token)) {
                $param = $settings->findOneBy(['setting' => 'api_token']);
                $param->setDefaultValue($api_token);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($api_username) && !empty($api_username)) {
                $param = $settings->findOneBy(['setting' => 'api_username']);
                $param->setDefaultValue($api_username);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($credit_dedipass_private_key) && !empty($credit_dedipass_private_key)) {
                $param = $settings->findOneBy(['setting' => 'credit_dedipass_private_key']);
                $param->setDefaultValue($credit_dedipass_private_key);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($credit_dedipass_public_key) && !empty($credit_dedipass_public_key)) {
                $param = $settings->findOneBy(['setting' => 'credit_dedipass_public_key']);
                $param->setDefaultValue($credit_dedipass_public_key);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($game_title) && !empty($game_title)) {
                $param = $settings->findOneBy(['setting' => 'game_title']);
                $param->setDefaultValue($game_title);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($seo_description) && !empty($seo_description)) {
                $param = $settings->findOneBy(['setting' => 'seo_description']);
                $param->setDefaultValue($seo_description);
                $entityManager->persist($param);
                $entityManager->flush();
            }
            if (isset($use_nav_community) && !empty($use_nav_community)) {
                $param = $settings->findOneBy(['setting' => 'use_nav_community']);
                $param->setDefaultValue($use_nav_community);

                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($use_right_community_button) && !empty($use_right_community_button)) {
                $param = $settings->findOneBy(['setting' => 'use_right_community_button']);
                $param->setDefaultValue($use_right_community_button);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($use_wiki) && !empty($use_wiki)) {
                $param = $settings->findOneBy(['setting' => 'use_wiki']);
                $param->setDefaultValue($use_wiki);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($facebook_link) && !empty($facebook_link)) {
                $param = $settings->findOneBy(['setting' => 'facebook_link']);
                $param->setDefaultValue($facebook_link);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($twitter_link) && !empty($twitter_link)) {
                $param = $settings->findOneBy(['setting' => 'twitter_link']);
                $param->setDefaultValue($twitter_link);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($youtube_link) && !empty($youtube_link)) {
                $param = $settings->findOneBy(['setting' => 'youtube_link']);
                $param->setDefaultValue($youtube_link);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($instagram_link) && !empty($instagram_link)) {
                $param = $settings->findOneBy(['setting' => 'instagram_link']);
                $param->setDefaultValue($instagram_link);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            if (isset($discord_link) && !empty($discord_link)) {
                $param = $settings->findOneBy(['setting' => 'discord_link']);
                $param->setDefaultValue($discord_link);
                $entityManager->persist($param);
                $entityManager->flush();
            }

            $this->addFlash('success', $translator->trans('Vos paramètres ont bien été mis à jour.'));
            return $this->redirectToRoute('admin.settings');
        }
        return $this->render('admin/cms_settings/index.html.twig', [
            'params' => $settings->findAll()
        ]);
    }

    /**
     * @Route("/admin/items/{page}", name="admin.items")
     */
    public function items(Api $api, CmsShopRepository $shop, CmsNewsRepository $news, $page = 0): Response
    {
        $total = $api->getAllItems($page - 1)['total'];
        $total_page = ceil($total / 25);


        return $this->render('admin/items_list/index.html.twig', [
            'total_page' => $total_page,
            'items' => $api->getAllItems($page - 1)['entries'],
            'page_actuel' => $page - 1
        ]);
    }

    /**
     * @Route("admin/accounts/{page}", name="admin.account")
     */
    public function account(Api $api, CmsSettingsRepository $settings, Request $request, TranslatorInterface $translator, $page): Response
    {

        if ($request->isMethod('POST')) {
            $user_id = $request->request->get('user_id');
            $username = $request->request->get('username');
            $ban = $request->request->get('ban');
            $unban = $request->request->get('unban');
            $mute = $request->request->get('mute');
            $unmute = $request->request->get('unmute');

            if (isset($ban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->banAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été banni.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($unban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unBanAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été débanni.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($mute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->MuteAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été mute.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($unmute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unMuteAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été unmuted.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }
        }

        $total = $api->getAllUsers($page - 1)['Total'];
        $total_page = ceil($total / 25);


        return $this->render('admin/account/index.html.twig', [
            'total_page' => $total_page,
            'items' => $api->getAllUsers($page - 1)['Values'],
            'page_actuel' => $page - 1
        ]);
    }

    /**
     * @Route("admin/account/detail/{user}", name="admin.account.detail")
     */
    public function accountDetail(Api $api, CmsSettingsRepository $settings, Request $request, TranslatorInterface $translator, $user): Response
    {
        if ($request->isMethod('POST')) {
            $user_id = $request->request->get('user_id');
            $username = $request->request->get('username');
            $ban = $request->request->get('ban');
            $unban = $request->request->get('unban');
            $mute = $request->request->get('mute');
            $unmute = $request->request->get('unmute');

            if (isset($ban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->banAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été banni.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($unban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unBanAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été débanni.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($mute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->MuteAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été mute.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($unmute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unMuteAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été unmuted.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }
        }

        dd($api->getCharacters($user));

        return $this->render('admin/account/detail.html.twig', [
            'user' => $api->getUser($user),
            'characters' => $api->getCharacters($user),
            'maxCharacters' => $api->getServerConfig()['Player']['MaxCharacters']
        ]);
    }
}
