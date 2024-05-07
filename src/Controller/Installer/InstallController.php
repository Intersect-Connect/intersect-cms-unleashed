<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller\Installer;

use App\Settings\Api;
use App\Entity\CmsSettings;
use App\Repository\CmsNewsRepository;
use App\Repository\CmsShopRepository;
use App\Repository\CmsPagesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use App\Repository\CmsNewsCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Settings\Settings as SettingsCmsSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InstallController extends AbstractController
{

    public function __construct(
        protected SettingsCmsSettings $settings,
        protected Api $api,
        protected CacheInterface $cache,
        protected PaginatorInterface $paginator
    ) {
    }


    #[Route(path: '/installation', name: 'installation.home', requirements: ['_locale' => 'en|fr'])]
    public function index(CmsNewsRepository $newsRepo, CmsShopRepository $shopRepo, Api $api, Request $request, SettingsCmsSettings $settings): Response
    {
        return $this->render('Application/' . $this->settings->get('theme') . '/Installation/index.html.twig', []);
    }

    #[Route(path: '/installation/step/1', name: 'installation.one', requirements: ['_locale' => 'en|fr'])]
    public function one(Request $request): Response
    {
        $session = $request->getSession();

        if ($request->isMethod("POST")) {
            $username = $request->request->get("username");
            $password = $request->request->get("password");
            $ip = $request->request->get("ip");
            $version = $request->request->get("version");
            $port = $request->request->get("port");



            if (
                isset($username) && !empty($username) &&
                isset($password) && !empty($password) &&
                isset($ip) && !empty($ip) &&
                isset($port) && !empty($port) &&
                isset($version) && !empty($version)
            ) {
                $session->set("username", $username);
                $session->set("ip", $ip);
                $session->set("version", $version);
                $session->set("port", $port);

                ## Set username
                $this->settings->setSetting("api_username", $username);
                ## Set username
                $this->settings->setSetting("api_password", hash("SHA256", $password));
                ## Set username
                if($version === "Intersect V6" || $version === "Intersect V7"){
                    $this->settings->setSetting("api_server", "https://". $ip . ":" . $port);
                }

                if($version === "Intersect V8"){
                    $this->settings->setSetting("api_server", "https://". $ip . ":" . $port);
                }

                ## Check If server is online
                $status = $this->api->ServeurStatut();

                if($status["success"]){
                    return $this->redirectToRoute('installation.two');
                }else{
                    return $this->redirectToRoute('installation.one.error');
                }


            }
        }
        return $this->render('Application/' . $this->settings->get('theme') . '/Installation/one.html.twig', [
            "username" => $session->get("username"),
            "ip" => $session->get("ip"),
            "version" => $session->get("version"),
            "port" => $session->get("port")

        ]);
    }

    #[Route(path: '/installation/step/1/error', name: 'installation.one.error', requirements: ['_locale' => 'en|fr'])]
    public function oneError(Request $request): Response
    {
        
        return $this->render('Application/' . $this->settings->get('theme') . '/Installation/one-error.html.twig', []);
    }
    

    #[Route(path: '/installation/step/2', name: 'installation.two', requirements: ['_locale' => 'en|fr'])]
    public function twoFull(Request $request): Response
    {
        if ($request->isMethod("POST")) {
            $username = $request->request->get("username");
            $password = $request->request->get("password");
            $ip = $request->request->get("ip");

            if (
                isset($username) && !empty($username) &&
                isset($password) && !empty($password) &&
                isset($ip) && !empty($ip)
            ) {
                ## Set username
                $this->settings->setSetting("api_username", $username);
                ## Set username
                $this->settings->setSetting("api_password", hash("SHA256", $password));
                ## Set username
                $this->settings->setSetting("api_server", $ip);
            }
        }

        return $this->render('Application/' . $this->settings->get('theme') . '/Installation/two-full.html.twig', []);
    }
}
