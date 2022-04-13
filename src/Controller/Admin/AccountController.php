<?php

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Settings\CmsSettings;
use App\Repository\CmsSettingsRepository;
use App\Repository\UserRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/accounts")
 */
class AccountController extends AbstractController
{

    private $settings;
    private $api;
    private $cache;
    private $paginator;
    

    public function __construct(CmsSettings $setting, Api $api, CacheInterface $cache, PaginatorInterface $paginator)
    {
        $this->settings = $setting;
        $this->api = $api;
        $this->cache = $cache;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/", name="admin.account")
     */
    public function account(Api $api, CmsSettingsRepository $settings, Request $request, TranslatorInterface $translator, PaginatorInterface $paginator): Response
    {

        if ($request->isMethod('POST')) {
            $user_id = $request->request->get('user_id');
            $username = $request->request->get('username');
            $ban = $request->request->get('ban');
            $unban = $request->request->get('unban');
            $mute = $request->request->get('mute');
            $unmute = $request->request->get('unmute');

            if (isset($ban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->banAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été banni.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($unban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unBanAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été débanni.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($mute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->MuteAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été mute.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($unmute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unMuteAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été unmuted.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }
        }

        $usersRequest = $this->cache->get('accounts', function (ItemInterface $item)  {
            return $this->api->multipleGetUsers();
        });
       

        $users = $paginator->paginate(
            $usersRequest, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            30 // Nombre de résultats par page
        );


        return $this->render('AdminPanel/account/index.html.twig', [
            'items' => $users,
        ]);
    }

    /**
     * @Route("/detail/{user}", name="admin.account.detail")
     */
    public function accountDetail(Api $api, CmsSettingsRepository $settings, Request $request, TranslatorInterface $translator, $user, CmsSettings $setting, UserRepository $userRepo): Response
    {
        if ($request->isMethod('POST')) {
            $user_id = $request->request->get('user_id');
            $username = $request->request->get('username');
            $ban = $request->request->get('ban');
            $unban = $request->request->get('unban');
            $mute = $request->request->get('mute');
            $unmute = $request->request->get('unmute');

            if (isset($ban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->banAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été banni.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($unban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unBanAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été débanni.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($mute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->MuteAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été mute.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($unmute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($api->unMuteAccount($user_id, $username)) {
                    $this->addFlash('success', $translator->trans('Le compte ' . $username . ' a bien été unmuted.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }
        }

        $userData = [
            "web" => $userRepo->findOneBy(["id" => $user]),
            "game" => $api->getUser($user)
        ];

        return $this->render('AdminPanel/account/detail.html.twig', [
            'user' => $userData,
            'characters' => $api->getCharacters($user),
            'maxCharacters' => $api->getServerConfig()['Player']['MaxCharacters']
        ]);
    }

    /**
     * @Route("add-points", name="admin.account.add.point")
     */
    public function addPoint(Request $request, UserRepository $userRepo, TranslatorInterface $translator){
        if ($request->isMethod('POST')) {
            $points = $request->request->get("points");
            $user = $request->request->get("user");

            if(
                isset($points) && $points > 0 &&
                isset($user) && !empty($user)
            ){
                $getUser = $userRepo->findOneBy(["id" => $user]);

                if($getUser){
                    $getUser->setPoints($points);
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('success', $translator->trans('Les points ont bien été crédités.'));
                    return $this->redirectToRoute('admin.account.detail', ["user" => $user]);

                }else{
                    ## User not find
                    $this->addFlash('error', $translator->trans('Une erreur s\'est produite.'));
                    return $this->redirectToRoute('admin.account.detail', ["user" => $user]);
                }
            }
        }
    }
}
