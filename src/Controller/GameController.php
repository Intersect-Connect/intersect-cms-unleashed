<?php

namespace App\Controller;

use App\Settings\Api;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

class GameController extends AbstractController
{
    /**
     * @Route("/{_locale}/players", name="game.players.liste",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueurs(Api $api, $page = 0, PaginatorInterface $paginator, Request $request): Response
    {
        $serveur_statut = $api->ServeurStatut();

        if ($serveur_statut['success']) {

            $joueurs = $api->getAllPlayers($page);


            $total_joueurs = $joueurs['Total'];
            $par_page = 30;
            $total_page = floor($total_joueurs / $par_page);


            $joueurs_liste = [];


            $time_start = microtime(true);


            for ($i = 0; $i <= $total_page; $i++) {
                foreach ($joueurs['Values'] as $joueur) {

                    if ($joueur['Level'] >= 1 && $joueur['Name'] != "Admin") {
                        $joueurs_liste[] = ['user' => $joueur['UserId'], 'name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                    }
                }
            }



            $data = ['joueurs' => $joueurs_liste, 'date' => new DateTime()];


            $par_page = 30;
            $total_page = count((array)$joueurs_liste);

            $joueurs = $paginator->paginate(
                $joueurs_liste, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );

            $response = new Response($this->renderView('game/players.html.twig', [
                'joueurs' => $joueurs,
                'max' => $total_page,
                'page_actuel' => $page
            ]));

            $response->setPublic();
            $response->setSharedMaxAge(3600);
            $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

            return $response;


            return $this->render('game/players.html.twig', [
                'joueurs' => $joueurs,
                'max' => $total_page,
                'page_actuel' => $page
            ]);
        } else {
            return $this->render('game/players.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/{_locale}/online-players", name="game.players.liste.online",  requirements={"_locale": "en|fr"})
     */
    public function listeJoueursEnLigne(Api $api, $page = 0): Response
    {
        $serveur_statut = $api->ServeurStatut();
        if ($serveur_statut['success']) {
            $joueurs = $api->onlinePlayers();

            $joueurs_liste = [];

            foreach ($joueurs as $joueur) {
                $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
            }

            $response = new Response($this->renderView('game/online.html.twig', [
                'joueurs' => $joueurs_liste,
            ]));

            $response->setPublic();
            $response->setSharedMaxAge(60);
            $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

            return $response;

            return $this->render('game/online.html.twig', [
                'joueurs' => $joueurs_liste,
            ]);
        } else {
            return $this->render('game/online.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }

    /**
     * @Route("/{_locale}/rank/level", name="game.rank.level",  requirements={"_locale": "en|fr"})
     */
    public function rankNiveau(Api $api): Response
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

            $response = new Response($this->renderView('game/level_rank.html.twig', [
                  'joueurs' => $joueurs_liste,
            ]));

            $response->setPublic();
            $response->setSharedMaxAge(3600);
            $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

            return $response;

            return $this->render('game/level_rank.html.twig', [
                'joueurs' => $joueurs_liste,
            ]);
        } else {
            return $this->render('game/level_rank.html.twig', [
                'serveur_statut' => false
            ]);
        }
    }
}
