<?php

namespace App\Controller;

use App\Entity\CmsSettings;
use App\Repository\CmsNewsCategoryRepository;
use App\Repository\CmsNewsRepository;
use App\Repository\CmsPagesRepository;
use App\Repository\CmsSettingsRepository;
use App\Repository\CmsShopRepository;
use App\Settings\Api;
use App\Settings\CmsSettings as SettingsCmsSettings;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function aside(Api $api, SettingsCmsSettings $settings, CmsNewsRepository $newRepo): Response
    {
        $serveur_statut = $api->ServeurStatut();

        if ($serveur_statut) {
            $serveur_online = true;

            return $this->render($settings->get('theme') . '/includes/aside.html.twig', [
                'serveur_online' => $serveur_online,
                'players_count' => $serveur_statut['online'],
                'news' => $newRepo->findBy([], ['id' => 'DESC'], 5)
            ]);
        } else {
            $serveur_online = false;
            return $this->render($settings->get('theme') . '/includes/aside.html.twig', [
                'serveur_online' => $serveur_online,
                'news' => $newRepo->findBy([], ['id' => 'DESC'], 5)
            ]);
        }
    }


    /**
     * @Route("/", name="home",  requirements={"_locale": "en|fr"})
     */
    public function index(
        CmsNewsRepository $newsRepo,
        CmsShopRepository $shopRepo,
        Request $request,
        SettingsCmsSettings $settings
    ): Response {

        $routeParameters = $request->attributes->get('_route_params');

        if (!isset($routeParameters['_locale'])) {
            return $this->redirectToRoute('home', ['_locale' => $request->getLocale()]);
        }

        $shopItems = $shopRepo->findBy(['visible' => true], ['id' => 'DESC'], 2);

        return $this->render($settings->get('theme') . '/home/index.html.twig', [
            'news' => $newsRepo->findBy([], ['id' => 'DESC'], 2),
            'shop' => $shopItems,
        ]);
    }

    /**
     *  @Route("/news", name="home.news",  requirements={"_locale": "en|fr"})
     */
    public function newsLists(CmsNewsRepository $newsRepo, PaginatorInterface $paginator, Request $request, SettingsCmsSettings $settings, CmsNewsCategoryRepository $categoryRepo): Response
    {
        if ($request->query->get('category')) {
            $news = $paginator->paginate(
                $newsRepo->findBy(['category' => $request->query->get('category')], ['id' => 'DESC']), // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                6 // Nombre de résultats par page
            );
        } else {
            $news = $paginator->paginate(
                $newsRepo->findBy([], ['id' => 'DESC']), // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                6 // Nombre de résultats par page
            );
        }


        return $this->render($settings->get('theme') . '/home/news.html.twig', [
            'news' => $news,
            'categorys' => $categoryRepo->findAll()
        ]);
    }


    /**
     *  @Route("/news/{id}-{slug}", name="news.read",  requirements={"_locale": "en|fr"})
     */
    public function newsRead(CmsNewsRepository $newsRepo, $id, SettingsCmsSettings $settings): Response
    {
        return $this->render($settings->get('theme') . '/home/read_news.html.twig', [
            'news' => $newsRepo->find($id),
        ]);
    }

    /**
     * @Route("/download", name="game.download",  requirements={"_locale": "en|fr"})
     */
    public function downloadRead(CmsPagesRepository $pageRepo, SettingsCmsSettings $settings): Response
    {
        return $this->render($settings->get('theme') . '/game/download.html.twig', [
            'news' => $pageRepo->findOneBy(['uniqueSlug' => 'download']),
        ]);
    }


    /**
     * @Route("/page/{slug}", name="game.pages",  requirements={"_locale": "en|fr"})
     */
    public function pageRead(CmsPagesRepository $pageRepo, $slug, SettingsCmsSettings $settings): Response
    {
        return $this->render($settings->get('theme') . '/game/pageRead.html.twig', [
            'news' => $pageRepo->findOneBy(['uniqueSlug' => $slug]),
        ]);
    }

    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */

    public function changeLocale($locale, Request $request, SettingsCmsSettings $settings)
    {
        $previous = $request->headers->get('referer');
        $local = $request->getLocale();

        $previous = str_replace('/' . $local . '/', '/' . $locale . '/', $previous);

        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($previous);
    }
}
