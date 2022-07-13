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
    private $paginator;
    private $api;
    private $cache;
    private $settings;

    public function __construct(PaginatorInterface $paginator, Api $api, CacheInterface $cache, CmsSettings $settings)
    {
        $this->paginator = $paginator;
        $this->api = $api;
        $this->cache = $cache;
        $this->settings = $settings;
        $this->theme = $this->settings->get('theme');
    }

    /**
     * @Route("/players", name="game.players.liste",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueurs(Request $request): Response
    {
        $serveur_statut = $this->api->ServeurStatut();

        if ($serveur_statut) {

            $joueurs = $this->api->getAllPlayers(0);


            $joueurs_liste = $this->cache->get('player_lists', function (ItemInterface $item) use ($joueurs) {
                $item->expiresAfter(86400);
                $total_joueurs = $joueurs['Total'];
                $par_page = 30;
                $total_page = floor($total_joueurs / $par_page);

                $joueurs_liste = [];

                for ($i = 0; $i <= $total_page; $i++) {
                    $joueurs = $this->api->getAllPlayers($i);

                    foreach ($joueurs['Values'] as $joueur) {

                        if ($joueur['Level'] >= 1 && $joueur['Name'] != "Admin") {
                            $joueurs_liste[] = ['user' => $joueur['UserId'], 'name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                        }
                    }
                }

                return $joueurs_liste;
            });


            $joueurs = $this->paginator->paginate(
                $joueurs_liste, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );

            return $this->render($this->theme . '/game/players.html.twig', [
                'joueurs' => $joueurs,
            ]);
        } else {
            return $this->render($this->theme . '/game/players.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/online-players", name="game.players.liste.online",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueursEnLigne(): Response
    {
        $serveur_statut = $this->api->ServeurStatut();
        if ($serveur_statut) {

            $joueurs = $this->cache->get('online_players', function (ItemInterface $item)  {
                $item->expiresAfter(1800);

                $joueurs = $this->api->onlinePlayers();

                $joueurs_liste = [];

                foreach ($joueurs as $joueur) {
                    $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                }

                return $joueurs_liste;
            });

            return $this->render($this->theme . '/game/online.html.twig', [
                'joueurs' => $joueurs,
            ]);
        } else {
            return $this->render($this->theme . '/game/online.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/rank/level", name="game.rank.level",  requirements={"_locale": "en|fr"})
     */
    public function rankNiveau(): Response
    {
        $serveur_statut = $this->api->ServeurStatut();
        if ($serveur_statut) {
            $joueurs = $this->cache->get('rank_level_players', function (ItemInterface $item) {
                $item->expiresAfter(86400);
                $joueurs = $this->api->getRank();

                $joueurs_liste = [];

                foreach ($joueurs as $joueur) {

                    if ($joueur['Name'] != "Admin" && $joueur['Level'] != "0") {
                        $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                    }
                }

                return $joueurs_liste;
            });

            return $this->render($this->theme . '/game/level_rank.html.twig', [
                'joueurs' => $joueurs
            ]);
        } else {
            return $this->render($this->theme . '/game/level_rank.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }
}
