<?php

namespace App\Controller;

use App\Entity\CmsSettings;
use App\Repository\CmsNewsRepository;
use App\Repository\CmsPagesRepository;
use App\Repository\CmsSettingsRepository;
use App\Settings\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function aside(Api $api, CmsSettingsRepository $settings): Response
    {
        $serveur_statut = $api->ServeurStatut();

        if ($serveur_statut['success']) {
            $serveur_online = true;
            return $this->render('includes/aside.html.twig', [
                'serveur_online' => $serveur_online,
                'players_count' => $serveur_statut['online']
            ]);
        } else {
            $serveur_online = false;
            return $this->render('includes/aside.html.twig', [
                'serveur_online' => $serveur_online
            ]);
        }
    }

    /**
     * @Route("/", name="home")
     */
    public function index(CmsNewsRepository $newsRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'news' => $newsRepo->findAll(),
        ]);
    }


    /**
     * @Route("/news/{id}-{slug}", name="news.read")
     */
    public function newsRead(CmsNewsRepository $newsRepo, $id): Response
    {
        $default_language = "fr";
        $time = time() + 6 * 30 * 24 * 3600;
        setcookie('language', 'fr', $time, '/', "", false, true);



        return $this->render('home/read_news.html.twig', [
            'news' => $newsRepo->find($id),
        ]);
    }

    /**
     * @Route("/download", name="game.download")
     */
    public function downloadRead(CmsPagesRepository $pageRepo): Response
    {
        return $this->render('game/download.html.twig', [
            'news' => $pageRepo->findOneBy(['uniqueSlug' => 'download']),
        ]);
    }


    /**
     * @Route("/page/{slug}", name="game.pages")
     */
    public function pageRead(CmsPagesRepository $pageRepo, $slug): Response
    {
        return $this->render('game/pageRead.html.twig', [
            'news' => $pageRepo->findOneBy(['uniqueSlug' => $slug]),
        ]);
    }

    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */

    public function changeLocale($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
