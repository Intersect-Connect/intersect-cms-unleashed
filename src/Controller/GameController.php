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
use App\Settings\CmsSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class GameController extends AbstractController
{
    /**
     * @Route("/players", name="game.players.liste",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueurs(Api $api, $page = 0, PaginatorInterface $paginator, Request $request, CmsSettings $settings, CacheInterface $cache): Response
    {
        $serveur_statut = $api->ServeurStatut();

        if ($serveur_statut['success']) {

            $players = $cache->get('players', function (ItemInterface $item) use ($api, $paginator, $request) {
                $item->expiresAfter(86400);
                $playersRequests = $api->multipleGetPlayers();
                $players_lists = [];

                foreach ($playersRequests as $player) {
                    if ($player['Level'] >= 1 && $player['Name'] != "Admin") {
                        $players_lists[] = ['user' => $player['UserId'], 'name' => $player['Name'], 'level' => $player['Level'], 'exp' => $player['Exp'], 'expNext' => $player['ExperienceToNextLevel']];
                    }
                }
                return $players_lists;
            });

            $playersArray = $paginator->paginate(
                $players, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );

            return $this->render($settings->get('theme') . '/game/players.html.twig', [
                'players' => $playersArray
            ]);
        } else {
            return $this->render($settings->get('theme') . '/game/players.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/online-players", name="game.players.liste.online",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueursEnLigne(Api $api, $page = 0, CmsSettings $settings): Response
    {
        $serveur_statut = $api->ServeurStatut();
        if ($serveur_statut['success']) {
            $joueurs = $api->onlinePlayers(0);

            $joueurs_liste = [];

            foreach ($joueurs as $joueur) {
                $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
            }

            $response = new Response($this->renderView($settings->get('theme') . '/game/online.html.twig', [
                'joueurs' => $joueurs_liste,
            ]));

            $response->setPublic();
            $response->setSharedMaxAge(60);
            $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

            return $response;
        } else {
            return $this->render($settings->get('theme') . '/game/online.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/rank/level", name="game.rank.level",  requirements={"_locale": "en|fr"})
     */
    public function rankNiveau(Api $api, CmsSettings $settings, CacheInterface $cache, PaginatorInterface $paginator, Request $request): Response
    {
        $serveur_statut = $api->ServeurStatut();
        if ($serveur_statut['success']) {

            $players = $cache->get('ranked_players', function (ItemInterface $item) use ($api, $paginator, $request) {
                $item->expiresAfter(86400);
                $rankRequests = $api->getRank(0);

                $players_lists = [];

                foreach ($rankRequests as $player) {

                    if ($player['Name'] != "Admin" && $player['Level'] != "0") {
                        $players_lists[] = ['name' => $player['Name'], 'level' => $player['Level'], 'exp' => $player['Exp'], 'expNext' => $player['ExperienceToNextLevel']];
                    }
                }

                return $players_lists;
            });

            return $this->render($settings->get('theme') . '/game/level_rank.html.twig', [
                'players' => $players
            ]);
        } else {
            return $this->render($settings->get('theme') . '/game/level_rank.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }
}
