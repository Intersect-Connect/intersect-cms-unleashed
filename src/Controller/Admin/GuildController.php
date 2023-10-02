<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Settings\Settings as CmsSettings;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: '/admin/guilds')]
class GuildController extends AbstractController
{
    private $settingsCms;
    private $api;
    private $cache;
    private $paginator;
    

    public function __construct(CmsSettings $settingCms, Api $api, CacheInterface $cache, PaginatorInterface $paginator)
    {
        $this->settingsCms = $settingCms;
        $this->api = $api;
        $this->cache = $cache;
        $this->paginator = $paginator;
    }


    #[Route(path: '/', name: 'admin.guilds')]
    public function index(): Response
    {
        $guildsRequest = $this->api->getAllGuilds();
        
        return $this->render('Admin/guild/index.html.twig', [
            'guilds' => $guildsRequest["Values"],
        ]);
    }

    #[Route(path: '/{id}', name: 'admin_guild.get')]
    public function get($id): Response
    {
        $guildRequest = $this->api->getGuild($id);

        return $this->render('Admin/guild/get.html.twig', [
            'guild' => $guildRequest,
        ]);
    }
}
