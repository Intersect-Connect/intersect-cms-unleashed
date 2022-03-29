<?php

/**
 * Intersect CMS Unleashed
 * 2.3 Update
 * Last modify : 29/03/2022 at 13:23
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Settings\CmsSettings;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/guilds")
 */
class GuildController extends AbstractController
{
    private $settingsCms;
    private $api;

    public function __construct(CmsSettings $settingCms, Api $api)
    {
        $this->settingsCms = $settingCms;
        $this->api = $api;
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
