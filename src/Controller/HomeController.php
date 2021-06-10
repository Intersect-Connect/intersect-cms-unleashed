<?php

namespace App\Controller;

use App\Entity\CmsSettings;
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

        if ($serveur_statut['success']) {
            $serveur_online = true;

            return $this->render($settings->get('theme') .'/includes/aside.html.twig', [
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
    public function index(CmsNewsRepository $newsRepo, CmsShopRepository $shopRepo, Api $api, Request $request, SettingsCmsSettings $settings): Response
    {

        $routeParameters = $request->attributes->get('_route_params');

        if (!isset($routeParameters['_locale'])) {
            return $this->redirectToRoute('home', ['_locale' => $request->getLocale()]);
        }

        $shopItems = $shopRepo->findBy(['visible' => true], ['id' => 'DESC'], 2);

        $shop = array();

        foreach ($shopItems as $itemShop) {

            $itemData = $api->getObjectDetail($itemShop->getIdItem());


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

        return $this->render($settings->get('theme') . '/home/index.html.twig', [
            'news' => $newsRepo->findBy([], ['id' => 'DESC'], 2),
            'shop' => $shop,
        ]);
    }

    /**
     *  @Route("/news", name="home.news",  requirements={"_locale": "en|fr"})
     */
    public function newsLists(CmsNewsRepository $newsRepo, PaginatorInterface $paginator, Request $request, SettingsCmsSettings $settings): Response
    {
        $news = $paginator->paginate(
            $newsRepo->findBy([], ['id' => 'DESC']), // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render($settings->get('theme') . '/home/news.html.twig', [
            'news' => $news,
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
