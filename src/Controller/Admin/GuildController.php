<?php

/**
 * Intersect CMS Unleashed
 * 2.3 Update
 * Last modify : 04/04/2022 at 11:28
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Settings\CmsSettings;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/guilds")
 * @IsGranted("ROLE_ADMIN")
 */
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


    /**
     * @Route("/", name="admin.guilds")
     */
    public function index(): Response
    {
        $guildsRequest = $this->api->getAllGuilds();
        
        return $this->render($this->settingsCms->get('theme') . '/admin/guild/index.html.twig', [
            'guilds' => $guildsRequest["Values"],
        ]);
    }

    /**
     * @Route("/{id}", name="admin_guild.get")
     */
    public function get($id): Response
    {
        $guildRequest = $this->api->getGuild($id);
        // dd($guildRequest);

        return $this->render($this->settingsCms->get('theme') . '/admin/guild/get.html.twig', [
            'guild' => $guildRequest,
        ]);
    }
}
