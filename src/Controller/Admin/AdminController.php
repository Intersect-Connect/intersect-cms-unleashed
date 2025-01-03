<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller\Admin;

use DateTime;
use App\Settings\Api;
use App\Repository\CmsNewsRepository;
use App\Repository\CmsShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CmsSettingsRepository;
use App\Settings\Settings as CmsSettings;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings, 
        protected Api $api, 
        protected CacheInterface $cache, 
        protected PaginatorInterface $paginator,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator
        ){}
        
    #[Route(path: '/admin', name: 'admin')]
    public function index(CmsShopRepository $shop, CmsNewsRepository $news,): Response
    {
        $total_users = null;
        $total_players = null;
        $moyenne_play = [];
        $last_register = [];

        if (isset($this->api->getAllUsers(0)['Total'])) {
            $total_users = $this->api->getAllUsers(0);

            $par_page = 30;
            $total_page = floor($total_users['Total'] / $par_page);

            for ($i = 0; $i <= $total_page; $i++) {
                $users = $this->api->getAllUsers($i);
                foreach ($users['Values'] as $user) {
                    $last_register[] = ['id' => $user['Id'], 'username' => $user['Name'], 'date' => $user['RegistrationDate']];
                    $moyenne_play[] = $user['PlayTimeSeconds'];
                }
            }
        }

        if ($last_register != null) {
            usort($last_register, function ($a, $b) {
                return $b['date'] > $a['date'];
            });
        }




        if (isset($this->api->getAllPlayers(0)['Total'])) {
            $total_players = $this->api->getAllPlayers(0)['Total'];
        }

        $server_request = $this->api->getServerInfo();
        $server_info = [];

        if (!isset($server_request['error'])) {
            $server_info['uptime'] = $server_request['uptime'] / 1000;
            $server_info['cps'] = $server_request['cps'];
            $server_info['connectedClients'] = $server_request['connectedClients'];
            $server_info['onlineCount'] = $server_request['onlineCount'];
        } else {
            $server_info = null;
        }


        return $this->render('Admin/index.html.twig', [
            'total_users' => $total_users != null ? $total_users['Total'] : null,
            'total_players' => $total_players,
            'total_shop' => count($shop->findAll()),
            'total_news' => count($news->findAll()),
            'server_info' => $server_info,
            'total_playTime' => array_sum($moyenne_play),
            'moyenne_play' => array_sum($moyenne_play) > 0 ? array_sum($moyenne_play) / count($moyenne_play) : null,
            'last_register' => $last_register,
            'online_players' => $this->api->onlinePlayers(0)
        ]);
    }


    #[Route(path: 'admin/settings', name: 'admin.settings')]
    public function settings(CmsSettingsRepository $settings, Request $request): Response
    {
        if ($request->isMethod('POST')) {

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
            $theme = $request->request->get('theme');
            $max_level = $request->request->get('max_level');
            $tinymce_key = $request->request->get('tinymce_key');


            if (isset($api_password) && !empty($api_password)) {
                $param = $settings->findOneBy(['setting' => 'api_password']);
                $param->setDefaultValue($api_password);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($api_server) && !empty($api_server)) {
                $param = $settings->findOneBy(['setting' => 'api_server']);
                $param->setDefaultValue($api_server);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($api_token) && !empty($api_token)) {
                $param = $settings->findOneBy(['setting' => 'api_token']);
                $param->setDefaultValue($api_token);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($api_username) && !empty($api_username)) {
                $param = $settings->findOneBy(['setting' => 'api_username']);
                $param->setDefaultValue($api_username);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($credit_dedipass_private_key) && !empty($credit_dedipass_private_key)) {
                $param = $settings->findOneBy(['setting' => 'credit_dedipass_private_key']);
                $param->setDefaultValue($credit_dedipass_private_key);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($credit_dedipass_public_key) && !empty($credit_dedipass_public_key)) {
                $param = $settings->findOneBy(['setting' => 'credit_dedipass_public_key']);
                $param->setDefaultValue($credit_dedipass_public_key);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($game_title) && !empty($game_title)) {
                $param = $settings->findOneBy(['setting' => 'game_title']);
                $param->setDefaultValue($game_title);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($seo_description) && !empty($seo_description)) {
                $param = $settings->findOneBy(['setting' => 'seo_description']);
                $param->setDefaultValue($seo_description);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }
            if (isset($use_nav_community) && !empty($use_nav_community)) {
                $param = $settings->findOneBy(['setting' => 'use_nav_community']);
                $param->setDefaultValue($use_nav_community);

                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($use_right_community_button) && !empty($use_right_community_button)) {
                $param = $settings->findOneBy(['setting' => 'use_right_community_button']);
                $param->setDefaultValue($use_right_community_button);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($use_wiki) && !empty($use_wiki)) {
                $param = $settings->findOneBy(['setting' => 'use_wiki']);
                $param->setDefaultValue($use_wiki);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($facebook_link) && !empty($facebook_link)) {
                $param = $settings->findOneBy(['setting' => 'facebook_link']);
                $param->setDefaultValue($facebook_link);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($twitter_link) && !empty($twitter_link)) {
                $param = $settings->findOneBy(['setting' => 'twitter_link']);
                $param->setDefaultValue($twitter_link);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($youtube_link) && !empty($youtube_link)) {
                $param = $settings->findOneBy(['setting' => 'youtube_link']);
                $param->setDefaultValue($youtube_link);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($instagram_link) && !empty($instagram_link)) {
                $param = $settings->findOneBy(['setting' => 'instagram_link']);
                $param->setDefaultValue($instagram_link);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($discord_link) && !empty($discord_link)) {
                $param = $settings->findOneBy(['setting' => 'discord_link']);
                $param->setDefaultValue($discord_link);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($theme) && !empty($theme)) {
                $param = $settings->findOneBy(['setting' => 'theme']);
                $param->setDefaultValue($theme);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($max_level) && !empty($max_level)) {
                $param = $settings->findOneBy(['setting' => 'max_level']);
                $param->setDefaultValue($max_level);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }

            if (isset($tinymce_key) && !empty($tinymce_key)) {
                $param = $settings->findOneBy(['setting' => 'tinymce_key']);
                $param->setDefaultValue($tinymce_key);
                $this->entityManager->persist($param);
                $this->entityManager->flush();
            }



            $this->addFlash('success', $this->translator->trans('Vos paramètres ont bien été mis à jour.'));
            return $this->redirectToRoute('admin.settings');
        }

        $dir    = '../templates/Application';
        $folders = scandir($dir);
        array_splice($folders, array_search('.', $folders), 1);
        array_splice($folders, array_search('..', $folders), 1);
        
        $settingsCat = [
            "website" => [
                "base_url",
                "credit_dedipass_private_key",
                "credit_dedipass_public_key",
                "current_lang",
                "game_title",
                "seo_description",
                "theme",
                "use_custom_game_pages",
                "use_nav_community",
                "use_right_community_button",
                "use_wiki",
                "tinymce_key",
                "max_level"
            ],
            "api" => [
                "api_username",
                "api_password",
                "api_server"
            ],
            "social" => [
                "facebook_link",
                "twitter_link",
                "youtube_link",
                "instagram_link",
                "discord_link"
            ]
        ];

        return $this->render('Admin/cms_settings/index.html.twig', [
            'params' => $settings->findAll(),
            'folders' => $folders,
            "settingsCat" => $settingsCat
        ]);
    }

    #[Route(path: '/admin/items/{page}', name: 'admin.items')]
    public function items(int $page = 0): Response
    {
        $items = $this->api->getAllItems($page);
        $total = $items['total'];
        $total_page = floor($total / 20);


        return $this->render('Admin/items_list/index.html.twig', [
            'total_page' => $total_page,
            'items' => $items['entries'],
            'page_actuel' => $page
        ]);
    }
}
