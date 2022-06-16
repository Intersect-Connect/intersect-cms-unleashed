<?php

namespace App\Controller;

use App\Settings\Api;
use App\Settings\CmsSettings;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

class GameController extends AbstractController
{
    /**
     * @Route("/players", name="game.players.liste",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueurs(Api $api,  PaginatorInterface $paginator, Request $request, CmsSettings $settings, CacheInterface $cache): Response
    {
        $serveur_statut = $api->ServeurStatut();

        if ($serveur_statut['success']) {

            $joueurs = $api->getAllPlayers(0);


            $joueurs_liste = $cache->get('player_lists', function (ItemInterface $item) use ($api, $joueurs) {
                $total_joueurs = $joueurs['Total'];
                $par_page = 30;
                $total_page = floor($total_joueurs / $par_page);

                $joueurs_liste = [];

                for ($i = 0; $i <= $total_page; $i++) {
                    $joueurs = $api->getAllPlayers($i);

                    foreach ($joueurs['Values'] as $joueur) {

                        if ($joueur['Level'] >= 1 && $joueur['Name'] != "Admin") {
                            $joueurs_liste[] = ['user' => $joueur['UserId'], 'name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                        }
                    }
                }

                return $joueurs_liste;
            });


            $joueurs = $paginator->paginate(
                $joueurs_liste, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );

            return $this->render($settings->get('theme') . '/game/players.html.twig', [
                'joueurs' => $joueurs,
                // 'max' => $total_page,
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
    public function listeJoueursEnLigne(Api $api, CmsSettings $settings, CacheInterface $cache): Response
    {
        $serveur_statut = $api->ServeurStatut();
        if ($serveur_statut['success']) {

            $joueurs = $cache->get('level_rank_list', function (ItemInterface $item) use ($api) {
                $joueurs = $api->onlinePlayers();

                $joueurs_liste = [];

                foreach ($joueurs as $joueur) {
                    $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                }

                return $joueurs_liste;
            });

            return $this->render($settings->get('theme') . '/game/online.html.twig', [
                'joueurs' => $joueurs,
            ]);
        } else {
            return $this->render($settings->get('theme') . '/game/online.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/rank/level", name="game.rank.level",  requirements={"_locale": "en|fr"})
     */
    public function rankNiveau(Api $api, CmsSettings $settings): Response
    {
        $serveur_statut = $api->ServeurStatut();
        if ($serveur_statut['success']) {


            $joueurs = $api->getRank();

            $joueurs_liste = [];

            foreach ($joueurs as $joueur) {

                if ($joueur['Name'] != "Admin" && $joueur['Level'] != "0") {
                    $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                }
            }

            $response = new Response($this->renderView($settings->get('theme') . '/game/level_rank.html.twig', [
                'joueurs' => $joueurs_liste,
            ]));

            $response->setPublic();
            $response->setSharedMaxAge(3600);
            $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

            return $response;
        } else {
            return $this->render($settings->get('theme') . '/game/level_rank.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }
}
