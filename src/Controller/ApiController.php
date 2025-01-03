<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller;

use App\Services\ApiManager;
use App\Settings\Settings as CmsSettings;
use App\Repository\CmsNewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/api/v1')]
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

    #[Route(path: '/news', name: 'api.news', methods: ['GET'])]
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
