<?php

namespace App\Controller;

use App\Entity\CmsShopHistory;
use App\Repository\CmsShopRepository;
use App\Repository\UserRepository;
use App\Settings\Api;
use App\Settings\CmsSettings;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="shop.index",  requirements={"_locale": "en|fr"})
     */
    public function index(
        CmsShopRepository $shopRepo,
        PaginatorInterface $paginator,
        Request $request,
        CmsSettings $settings
    ): Response {

        $shopItems = $shopRepo->findBy(['visible' => true]);
        $items = $paginator->paginate(
            $shopItems, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render($settings->get('theme') . '/shop/index.html.twig', [
            'shop' => $items,
        ]);
    }

    /**
     * @Route("/shop/detail/{id}", name="shop.detail",  requirements={"_locale": "en|fr"})
     */
    public function detail(CmsShopRepository $shopRepo, Request $request, Api $api, $id, TranslatorInterface $translator, UserRepository $userRepo, CmsSettings $settings): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home.index');
        }

        $shopItem = $shopRepo->find($id);
        $itemData = $api->getObjectDetail($shopItem->getIdItem());

        if ($shopItem->getPromotion()) {
            $item = ['id' => $id, 'name' => $itemData['Name'], 'description' => $shopItem->getForcedDescription(), 'price' => $shopItem->getPrice() * (1 - ($shopItem->getPromotion() / 100)), 'quantity' => $shopItem->getQuantity(), 'icon' => $itemData['Icon'], 'image' => $shopItem->getImage()];
        } else {
            $item = ['id' => $id, 'name' => $itemData['Name'], 'description' => $shopItem->getForcedDescription(), 'price' => $shopItem->getPrice(), 'quantity' => $shopItem->getQuantity(), 'icon' => $itemData['Icon'], 'image' => $shopItem->getImage()];
        }

        $characters = $api->getCharacters($this->getUser()->getId());

        foreach ($characters as $key => $character) {
            if (!$api->isInventoryFull($character['Id'])) {
                $characters[$key]['inventoryFull'] = false;
            } else {
                $characters[$key]['inventoryFull'] = true;
            }
        }

        // Si la requête est bien POST
        if ($request->isMethod('POST')) {
            // On récupère la quantité voulu du joueur
            $quantity = $request->request->get('quantity');
            // On récupère l'id du personnage pour l'envoyez dans son inventaire
            $character = $request->request->get('playerShop');

            // Si la quantité n'est pas null et pas égal à 0 et que l'id du personnage existe est n'est pas vide
            if ($quantity != null || $quantity != 0 && isset($character) && !empty($character)) {
                // On prépare les données d'envoi api avec l'id de l'item, et la quantité

                if ($shopItem->getQuantity() > 1) {
                    $data = [
                        'itemId' => $shopItem->getIdItem(),
                        'quantity' => $shopItem->getQuantity() * $quantity,
                        'bankoverflow' => false
                    ];
                } else {
                    $data = [
                        'itemId' => $shopItem->getIdItem(),
                        'quantity' => $quantity,
                        'bankoverflow' => false
                    ];
                }

                // Si le nombre de point est supérieur ou égal au prix de l'objet
                if ($this->getUser()->getPoints() >= $shopItem->getPrice() * $quantity) {
                    //  alors on lance la requête d'achat, l'objet est envoyez dans l'inventaire et la requête doit retourner true
                    if ($api->giveItem($data, $character)) {
                        // Si la requête on retourne true, on récupère l'utilisateur actuel
                        $user = $userRepo->find($this->getUser());
                        // On définit le prix de l'objet actuel
                        $prix_objet = $shopItem->getPrice() - $shopItem->getPrice() * $shopItem->getPromotion() / 100;

                        if ($quantity == 1) {
                            $user->setPoints($user->getPoints() - $prix_objet);
                        } else {
                            $prix_objet_q = $prix_objet * $quantity;
                            $user->setPoints($user->getPoints() - $prix_objet_q);
                        }

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();

                        $boutiqueHistorique = new CmsShopHistory();
                        $boutiqueHistorique->setDate(new DateTime());
                        $boutiqueHistorique->setShopId($id);
                        $boutiqueHistorique->setUserId($this->getUser()->getId());
                        $boutiqueHistorique->setCreditsNow($user->getPoints());
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($boutiqueHistorique);
                        $entityManager->flush();

                        $this->addFlash('success', $translator->trans('Votre achat à bien été effectuer, vous devriez avoir reçu votre objet en jeu.'));
                        return $this->redirectToRoute('shop.detail', ['id' => $id]);
                    } else {
                        $this->addFlash('error', $translator->trans('Une erreur s\'est produit, veuillez réessayer.'));
                        return $this->redirectToRoute('shop.detail', ['id' => $id]);
                    }
                } else {
                    $this->addFlash('error', $translator->trans('Vous n\'avez pas assez de points.'));
                    return $this->redirectToRoute('shop.detail', ['id' => $id]);
                }
            } else {
                $this->addFlash('error', $translator->trans('Un champ est manquant. Vérifier la quantité et le personnage choisis.'));
                return $this->redirectToRoute('shop.detail', ['id' => $id]);
            }
        }
        return $this->render($settings->get('theme') . '/shop/detail.html.twig', [
            'item' => $item,
            'personnages' => $characters
        ]);
    }
}
