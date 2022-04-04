<?php

/**
 * Intersect CMS Unleashed
 * 2.3 Update
 * Last modify : 04/04/2022 at 12:03
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller;

use App\Services\ApiManager;
use App\Settings\CmsSettings;
use App\Repository\CmsNewsRepository;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/v1")
 */
class ApiController extends AbstractController
{
    private $apiManager;
    private $setting;
    private $router;


    public function __construct(ApiManager $apiManager, CmsSettings $setting, UrlGeneratorInterface $router)
    {
        $this->apiManager = $apiManager;
        $this->setting = $setting;
        $this->router = $router;
    }

    /**
     * @Route("/news", name="api.news", methods={"GET"})
     */
    public function index(CmsNewsRepository $newsRepo, Request $request): Response
    {
        $news = $newsRepo->findAll();

        if ($news) {
            $newsList = [];

            foreach ($news as $key => $news) {
                $newsList[] = [
                    "id" => $news->getId(),
                    "image" => $request->getSchemeAndHttpHost() . "/assets/general/news/" .  $news->getImgUrl(),
                    "title" => $news->getTitle(),
                    "link" => $this->router->generate("news.read", ["id" => $news->getId(), "slug" => $news->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL)
                ];
            }

            return $this->apiManager->generateResponse([
                "success" => true,
                "data" => $newsList
            ]);
        } else {
            return $this->apiManager->generateResponse([
                "success" => false,
                "data" => null
            ]);
        }
    }
}
