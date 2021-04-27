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

class GameController extends AbstractController
{
    /**
     * @Route("/joueurs/", name="game.players.liste")
     */
    public function listeJoueurs(Api $api, $page = 0, PaginatorInterface $paginator, Request $request): Response
    {
        $serveur_statut = $api->ServeurStatut();
        $filesystem = new Filesystem();

        if (!$filesystem->exists('../var/cache/game')) {
            $filesystem->mkdir('../var/cache/game', 0644);
        }



        $cachefile = '../var/cache/game/liste_joueurs.json';
        $cachetime = 3600; // time to cache in seconds
        $cacheDate = new DateTime();
        // dd($filesystem->exists($cachefile) && filectime($cachefile) > (time() - $cachetime));

        if ($filesystem->exists($cachefile) && filectime($cachefile) > (time() - $cachetime)) {
            $time_start = microtime(true);

            $jsonfile = file_get_contents($cachefile);

            $liste_joueur = json_decode($jsonfile)->joueurs;
            $date = json_decode($jsonfile)->date->date;

            $time_start = microtime(true);


            $joueurs = $paginator->paginate(
                $liste_joueur, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );

            return $this->render('game/players.html.twig', [
                'joueurs' => $joueurs,
                'cacheDate' => $date,
            ]);
        } else {
            if ($serveur_statut['success']) {

                $filesystem->remove($cachefile);


                $joueurs = $api->getAllPlayers($page);
                $total_joueurs = $joueurs['Total'];
                $par_page = 30;
                $total_page = round($total_joueurs / $par_page);

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
                $cache = fopen($cachefile, 'w');
                fwrite($cache, json_encode($data));
                fclose($cache);


                $jsonfile = file_get_contents($cachefile);

                $liste_joueur = json_decode($jsonfile)->joueurs;
                $liste_joueur = (array)$liste_joueur;

                foreach ($liste_joueur as $joueur) {
                        $liste_joueur[] = $joueur;
                }

                $par_page = 30;
                $total_page = count((array)$liste_joueur);
                $date = json_decode($jsonfile)->date->date;

                $joueurs = $paginator->paginate(
                    $liste_joueur, // Requête contenant les données à paginer (ici nos articles)
                    $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                    10 // Nombre de résultats par page
                );


                return $this->render('game/players.html.twig', [
                    'joueurs' => $joueurs,
                    'cacheDate' => $date,
                    'max' => $total_page,
                    'page_actuel' => $page
                ]);
            } else {
                return $this->render('game/players.html.twig', [
                    'serveur_statut' => false
                ]);
            }
        }
    }

    /**
     * @Route("/joueurs-connecte/", name="game.players.liste.online")
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
     * @Route("/classement-niveau/", name="game.rank.level")
     */
    public function rankNiveau(Api $api): Response
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists('../var/cache/game')) {
            $filesystem->mkdir('../var/cache/game', 0644);
        }

        $cachefile = '../var/cache/game/classement_general.json';
        $cachetime = 3600; // time to cache in seconds
        $cacheDate = new DateTime();

        if ($filesystem->exists($cachefile) && (strtotime(date("F d Y H:i:s.", filectime($cachefile))) > (time() - $cachetime))) {
            $liste_joueur = file_get_contents($cachefile);

            $liste_joueur = json_decode($liste_joueur);
            $date = $liste_joueur->date->date;
            return $this->render('game/level_rank.html.twig', [
                'joueurs' => $liste_joueur->joueurs,
                'cacheDate' => $date
            ]);
        } else {
            $serveur_statut = $api->ServeurStatut();
            if ($serveur_statut['success']) {
                if ($filesystem->exists($cachefile)) {
                    $filesystem->remove($cachefile);
                }
                $cache = fopen($cachefile, 'w');

                $joueurs = $api->getRank();

                $joueurs_liste = [];

                foreach ($joueurs as $joueur) {

                    if ($joueur['Name'] != "Admin" && $joueur['Level'] != "1") {
                        $joueurs_liste[] = ['name' => $joueur['Name'], 'level' => $joueur['Level'], 'exp' => $joueur['Exp'], 'expNext' => $joueur['ExperienceToNextLevel']];
                    }
                }

                $data = ['joueurs' => $joueurs_liste, 'date' => new DateTime()];
                fwrite($cache, json_encode($data));
                fclose($cache);

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
}
