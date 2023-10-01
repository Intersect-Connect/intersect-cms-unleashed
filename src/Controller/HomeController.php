<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller;

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

class HomeController extends AbstractController
{

    public function __construct(
        protected SettingsCmsSettings $settings, 
        protected Api $api, 
        protected CacheInterface $cache, 
        protected PaginatorInterface $paginator)
    {
    }

    public function aside(Api $api, SettingsCmsSettings $settings, CmsNewsRepository $newRepo): Response
    {
        $serveur_statut = $this->api->ServeurStatut();

        if ($serveur_statut['success']) {
            $serveur_online = true;

            return $this->render($this->settings->get('theme') . '/includes/aside.html.twig', [
                'serveur_online' => $serveur_online,
                'players_count' => $serveur_statut['online'],
                'news' => $newRepo->findBy([], ['id' => 'DESC'], 5)
            ]);
        } else {
            $serveur_online = false;
            return $this->render($this->settings->get('theme') . '/includes/aside.html.twig', [
                'serveur_online' => $serveur_online,
                'news' => $newRepo->findBy([], ['id' => 'DESC'], 5)
            ]);
        }
    }


    #[Route(path: '/', name: 'home', requirements: ['_locale' => 'en|fr'])]
    public function index(CmsNewsRepository $newsRepo, CmsShopRepository $shopRepo, Api $api, Request $request, SettingsCmsSettings $settings): Response
    {

        $routeParameters = $request->attributes->get('_route_params');

        if (!isset($routeParameters['_locale'])) {
            return $this->redirectToRoute('home', ['_locale' => $request->getLocale()]);
        }

        $shopItems = $shopRepo->findBy(['visible' => true], ['id' => 'DESC'], 2);

        $shop = array();

        foreach ($shopItems as $itemShop) {

            $itemData = $this->api->getObjectDetail($itemShop->getIdItem());


            $shop[$itemShop->getId()]['itemData'] = $itemData;
            if ($itemShop->getForcedDescription() != "") {
                $shop[$itemShop->getId()]['description'] = $itemShop->getForcedDescription();
            } else {
                $shop[$itemShop->getId()]['description'] = $itemData['Description'];
            }
            if ($itemShop->getPromotion() > 0) {
                $shop[$itemShop->getId()]['price'] = $itemShop->getPrice() * (1 - ($itemShop->getPromotion() / 100));
            } else {
                $shop[$itemShop->getId()]['price'] =  $itemShop->getPrice();
            }
            $shop[$itemShop->getId()]['quantity'] = $itemShop->getQuantity();
            $shop[$itemShop->getId()]['promotion'] = $itemShop->getPromotion();
            $shop[$itemShop->getId()]['id'] = $itemShop->getId();
            $shop[$itemShop->getId()]['name'] = $itemShop->getName();

            if ($itemShop->getImage() != null) {
                $shop[$itemShop->getId()]['image'] = $itemShop->getImage();
            } else {
                $shop[$itemShop->getId()]['image'] = null;
            }
        }

        return $this->render($this->settings->get('theme') . '/home/index.html.twig', [
            'news' => $newsRepo->findBy([], ['id' => 'DESC'], 2),
            'shop' => $shop,
        ]);
    }

    #[Route(path: '/news', name: 'home.news', requirements: ['_locale' => 'en|fr'])]
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


        return $this->render($this->settings->get('theme') . '/home/news.html.twig', [
            'news' => $news,
            'categorys' => $categoryRepo->findAll()
        ]);
    }


    #[Route(path: '/news/{id}-{slug}', name: 'news.read', requirements: ['_locale' => 'en|fr'])]
    public function newsRead(CmsNewsRepository $newsRepo, $id, SettingsCmsSettings $settings): Response
    {
        return $this->render($this->settings->get('theme') . '/home/read_news.html.twig', [
            'news' => $newsRepo->find($id),
        ]);
    }

    #[Route(path: '/download', name: 'game.download', requirements: ['_locale' => 'en|fr'])]
    public function downloadRead(CmsPagesRepository $pageRepo, SettingsCmsSettings $settings): Response
    {
        return $this->render($this->settings->get('theme') . '/game/download.html.twig', [
            'news' => $pageRepo->findOneBy(['uniqueSlug' => 'download']),
        ]);
    }


    #[Route(path: '/page/{slug}', name: 'game.pages', requirements: ['_locale' => 'en|fr'])]
    public function pageRead(CmsPagesRepository $pageRepo, $slug, SettingsCmsSettings $settings): Response
    {
        return $this->render($this->settings->get('theme') . '/game/pageRead.html.twig', [
            'news' => $pageRepo->findOneBy(['uniqueSlug' => $slug]),
        ]);
    }

    #[Route(path: '/change_locale/{locale}', name: 'change_locale')]
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
