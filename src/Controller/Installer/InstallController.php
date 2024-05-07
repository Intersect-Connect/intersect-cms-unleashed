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
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\CmsNewsCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Settings\Settings as SettingsCmsSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InstallController extends AbstractController
{

    public function __construct(
        protected SettingsCmsSettings $settings,
        protected Api $api,
        protected CacheInterface $cache,
        protected PaginatorInterface $paginator,
        protected ParameterBagInterface $param,
        protected EntityManagerInterface $em
    ) {
    }


    #[Route(path: '/installation', name: 'installation.home', requirements: ['_locale' => 'en|fr'])]
    public function index(CmsNewsRepository $newsRepo, CmsShopRepository $shopRepo, Api $api, Request $request, SettingsCmsSettings $settings): Response
    {
        $filesystem = new Filesystem();
        $dbIsReady = $filesystem->exists($this->param->get("default_project_path") . 'DB_NOT_READY');
        return $this->render('Application/BritaniaR/Installation/index.html.twig', [
            'phpversion' => phpversion(),
            'dbReady' => $dbIsReady
        ]);
    }

    #[Route(path: '/installation/step/1', name: 'installation.one', requirements: ['_locale' => 'en|fr'])]
    public function one(Request $request): Response
    {
        $session = $request->getSession();

        if ($request->isMethod("POST")) {

            try {
                $sqlContent = file_get_contents($this->param->get("default_project_path") . 'installation.sql');
                $connection = $this->em->getConnection();

                $statement = $connection->prepare($sqlContent);
                $statement->executeQuery();

                $filesystem = new Filesystem();
                $filesystem->remove([$this->param->get("default_project_path") . 'DB_NOT_READY']);


                return $this->redirectToRoute("installation.two");
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        return $this->render('Application/BritaniaR/Installation/sql.html.twig', [
            "username" => $session->get("username"),
            "ip" => $session->get("ip"),
            "version" => $session->get("version"),
            "port" => $session->get("port")

        ]);
    }

    #[Route(path: '/installation/step/2', name: 'installation.two', requirements: ['_locale' => 'en|fr'])]
    public function two(Request $request): Response
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
                ## Set password
                $this->settings->setSetting("api_password", hash("SHA256", $password));
                ## Set version
                if ($version === "Intersect V6" || $version === "Intersect V7") {
                    $this->settings->setSetting("api_server", "https://" . $ip . ":" . $port);
                }

                if ($version === "Intersect V8") {
                    $this->settings->setSetting("api_server", "https://" . $ip . ":" . $port);
                }

                ## Check If server is online
                $status = $this->api->ServeurStatut();

                if ($status["success"]) {
                    return $this->redirectToRoute('installation.three');
                } else {
                    return $this->redirectToRoute('installation.two.error');
                }
            }
        }
        return $this->render('Application/BritaniaR/Installation/two.html.twig', [
            "username" => $session->get("username"),
            "ip" => $session->get("ip"),
            "version" => $session->get("version"),
            "port" => $session->get("port")

        ]);
    }

    #[Route(path: '/installation/step/2/error', name: 'installation.two.error', requirements: ['_locale' => 'en|fr'])]
    public function twoError(Request $request): Response
    {

        return $this->render('Application/BritaniaR/Installation/two-error.html.twig', []);
    }


    #[Route(path: '/installation/step/3', name: 'installation.three', requirements: ['_locale' => 'en|fr'])]
    public function three(Request $request): Response
    {
        if ($request->isMethod("POST")) {
            $title = $request->request->get("title");
            $description = $request->request->get("description");

            if (
                isset($title) && !empty($title) &&
                isset($description) && !empty($description)
            ) {
                ## Set website title
                $this->settings->setSetting("game_title", $title);
                ## Set description
                $this->settings->setSetting("seo_description", $description);

                return $this->redirectToRoute("installation.four");
            }
        }

        return $this->render('Application/BritaniaR/Installation/three.html.twig', []);
    }

    #[Route(path: '/installation/step/4', name: 'installation.four', requirements: ['_locale' => 'en|fr'])]
    public function four(Request $request): Response
    {
        if ($request->isMethod("POST")) {
            $filesystem = new Filesystem();
            $filesystem->remove([$this->param->get("default_project_path") . 'ENABLE_INSTALLATION_TOOLS']);

            return $this->redirectToRoute('home');
        }

        return $this->render('Application/BritaniaR/Installation/four.html.twig', []);
    }
}
