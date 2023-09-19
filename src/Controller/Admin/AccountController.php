<?php

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Settings\CmsSettings;
use App\Repository\CmsSettingsRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/accounts")
 * @IsGranted("ROLE_ADMIN")
 */
class AccountController extends AbstractController
{

    public function __construct(
        protected Api $api,
        protected TranslatorInterface $translator,
        protected CacheInterface $cache
    ) {
    }

    /**
     * @Route("/", name="admin.account")
     */
    public function account(Request $request, PaginatorInterface $paginator): Response
    {

        if ($request->isMethod('POST')) {
            $user_id = $request->request->get('user_id');
            $username = $request->request->get('username');
            $ban = $request->request->get('ban');
            $unban = $request->request->get('unban');
            $mute = $request->request->get('mute');
            $unmute = $request->request->get('unmute');

            if (isset($ban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->banAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été banni.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($unban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->unBanAccount($user_id, $username)) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été débanni.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($mute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->MuteAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été mute.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }

            if (isset($unmute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->unMuteAccount($user_id, $username)) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été unmuted.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account', ['page' => 0]);
                }
            }
        }

        $users = $this->api->getAllUsers(0);
        $total = $users['Total'];
        $total_page = floor($total / 30);


        $allUser =  $this->cache->get('users', function (ItemInterface $item) {
            $item->expiresAfter(86400);
            return $this->api->multipleGetUsers();
        });

        $users = $paginator->paginate(
            $allUser, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );


        return $this->render('Admin/account/index.html.twig', [
            'total_page' => $total_page,
            'items' => $users,
            // 'page_actuel' => $page
        ]);
    }

    /**
     * @Route("/detail/{user}", name="admin.account.detail")
     */
    public function accountDetail(Request $request, $user): Response
    {
        if ($request->isMethod('POST')) {
            $user_id = $request->request->get('user_id');
            $username = $request->request->get('username');
            $ban = $request->request->get('ban');
            $unban = $request->request->get('unban');
            $mute = $request->request->get('mute');
            $unmute = $request->request->get('unmute');

            if (isset($ban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->banAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été banni.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($unban) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->unBanAccount($user_id, $username)) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été débanni.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($mute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->MuteAccount($user_id, $username, 5, $this->getUser()->getUsername())) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été mute.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }

            if (isset($unmute) && isset($username) && !empty($username) && isset($user_id) && !empty($user_id)) {
                if ($this->api->unMuteAccount($user_id, $username)) {
                    $this->addFlash('success', $this->translator->trans('Le compte ' . $username . ' a bien été unmuted.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                } else {
                    $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit.'));
                    return $this->redirectToRoute('admin.account.detail', ['user' => $user]);
                }
            }
        }

        return $this->render('Admin/account/detail.html.twig', [
            'user' => $this->api->getUser($user),
            'characters' => $this->api->getCharacters($user),
            'maxCharacters' => $this->api->getServerConfig()['Player']['MaxCharacters']
        ]);
    }
}
