<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Settings\Api;
use App\Entity\CmsShopHistory;
use App\Repository\UserRepository;
use App\Repository\CmsShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ShopController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings, 
        protected Api $api, 
        protected CacheInterface $cache, 
        protected PaginatorInterface $paginator,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        ){}
        
    #[Route(path: '/shop', name: 'shop.index', requirements: ['_locale' => 'en|fr'])]
    public function index(CmsShopRepository $shopRepo, Api $api, PaginatorInterface $paginator, Request $request, CmsSettings $settings): Response
    {

        $shopItems = $shopRepo->findBy(['visible' => true]);

        $shop = array();
        foreach ($shopItems as $itemShop) {

            $itemData = $this->api->getObjectDetail($itemShop->getIdItem());


            $shop[$itemShop->getId()]['itemData'] = $itemData;

            if ($itemShop->getForcedDescription() != "") {
                $shop[$itemShop->getId()]['description'] = $itemShop->getForcedDescription();
            } else {
                $shop[$itemShop->getId()]['description'] = $itemData['Description'];
            }
            if ($itemShop->getPromotion() > 0) {
                $shop[$itemShop->getId()]['price'] = $itemShop->getPrice() * (1 - ($itemShop->getPromotion() / 100));
            } else {
                $shop[$itemShop->getId()]['price'] =  $itemShop->getPrice();
            }
            $shop[$itemShop->getId()]['quantity'] = $itemShop->getQuantity();
            $shop[$itemShop->getId()]['promotion'] = $itemShop->getPromotion();
            $shop[$itemShop->getId()]['id'] = $itemShop->getId();



            $shop[$itemShop->getId()]['name'] = $itemShop->getName();

            if ($itemShop->getImage() != null) {
                $shop[$itemShop->getId()]['image'] = $itemShop->getImage();
            } else {
                $shop[$itemShop->getId()]['image'] = null;
            }
        }

        $items = $paginator->paginate(
            $shop, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('Application/' . $this->settings->get('theme') . '/shop/index.html.twig', [
            'shop' => $items,
        ]);
    }

    #[Route(path: '/shop/detail/{id}', name: 'shop.detail', requirements: ['_locale' => 'en|fr'])]
    public function detail(CmsShopRepository $shopRepo, Request $request, Api $api, $id, TranslatorInterface $translator, UserRepository $userRepo, CmsSettings $settings): Response
    {
        $shopItem = $shopRepo->find($id);
        $itemData = $this->api->getObjectDetail($shopItem->getIdItem());
        $user = $userRepo->find($this->getUser());

        if ($shopItem->getPromotion()) {
            $item = ['id' => $id, 'name' => $itemData['Name'], 'description' => $shopItem->getForcedDescription(), 'price' => $shopItem->getPrice() * (1 - ($shopItem->getPromotion() / 100)), 'quantity' => $shopItem->getQuantity(), 'icon' => $itemData['Icon'], 'image' => $shopItem->getImage()];
        } else {
            $item = ['id' => $id, 'name' => $itemData['Name'], 'description' => $shopItem->getForcedDescription(), 'price' => $shopItem->getPrice(), 'quantity' => $shopItem->getQuantity(), 'icon' => $itemData['Icon'], 'image' => $shopItem->getImage()];
        }

        $personnages = $this->api->getCharacters($user->getId());

        foreach ($personnages as $key => $personnage) {
            if (!$this->api->isInventoryFull($personnage['Id'])) {
                $personnages[$key]['inventoryFull'] = false;
            } else {
                $personnages[$key]['inventoryFull'] = true;
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
                if ($user->getPoints() >= $item['price'] * $quantity) {
                    //  alors on lance la requête d'achat, l'objet est envoyez dans l'inventaire et la requête doit retourner true
                    if ($this->api->giveItem($data, $character)) {
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

                        $this->entityManager->persist($user);
                        $this->entityManager->flush();

                        $boutiqueHistorique = new CmsShopHistory();
                        $boutiqueHistorique->setDate(new DateTime());
                        $boutiqueHistorique->setShopId($id);
                        $boutiqueHistorique->setUserId($user->getId());
                        $boutiqueHistorique->setCreditsNow($user->getPoints());
                        $this->entityManager->persist($boutiqueHistorique);
                        $this->entityManager->flush();

                        $this->addFlash('success', $this->translator->trans('Votre achat à bien été effectuer, vous devriez avoir reçu votre objet en jeu.'));
                        return $this->redirectToRoute('shop.detail', ['id' => $id]);
                    } else {
                        $this->addFlash('error', $this->translator->trans('Une erreur s\'est produit, veuillez réessayer.'));
                        return $this->redirectToRoute('shop.detail', ['id' => $id]);
                    }
                } else {
                    $this->addFlash('error', $this->translator->trans('Vous n\'avez pas assez de points.'));
                    return $this->redirectToRoute('shop.detail', ['id' => $id]);
                }
            } else {
                $this->addFlash('error', $this->translator->trans('Un champ est manquant. Vérifier la quantité et le personnage choisis.'));
                return $this->redirectToRoute('shop.detail', ['id' => $id]);
            }
        }
        return $this->render('Application/' . $this->settings->get('theme') . '/shop/detail.html.twig', [
            'item' => $item,
            'personnages' => $personnages
        ]);
    }
}
