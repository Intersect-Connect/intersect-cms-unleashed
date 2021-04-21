<?php

namespace App\Controller;

use App\Repository\CmsShopRepository;
use App\Settings\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="shop.index")
     */
    // if ($request->isMethod('post')) {
    public function index(CmsShopRepository $shopRepo, Api $api): Response
    {

        $shopItems = $shopRepo->findBy(['visible' => true]);

        $shop = array();
        foreach ($shopItems as $itemShop) {

            $itemData = $api->getObjectDetail($itemShop->getIdItem());


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
        }

        return $this->render('shop/index.html.twig', [
            'shop' => $shop,
        ]);
    }

    /**
     * @Route("/shop/detail/{id}", name="shop.detail")
     */
    public function detail(CmsShopRepository $shopRepo, Request $request, Api $api, $id, TranslatorInterface $translator): Response
    {
        $shopItem = $shopRepo->find($id);
        $itemData = $api->getObjectDetail($shopItem->getIdItem());

        $item = ['id' => $id, 'name' => $itemData['Name'], 'description' => $shopItem->getForcedDescription(), 'price' => $shopItem->getPrice(), 'quantity' => $shopItem->getQuantity(), 'icon' => $itemData['Icon']];

        $personnages = $api->getCharacters($this->getUser()->getId());

        foreach ($personnages as $key => $personnage) {
            if (!$api->isInventoryFull($personnage['Id'])) {
                $personnages[$key]['inventoryFull'] = false;
            } else {
                $personnages[$key]['inventoryFull'] = true;
            }
        }

        if ($request->isMethod('POST')) {
            $quantity = $request->request->get('quantity');
            $character = $request->request->get('playerShop');

            if ($quantity != null || $quantity != 0 && isset($character) && !empty($character)) {

                $data = [
                    'itemId' => $shopItem->getIdItem(),
                    'quantity' => $quantity,
                    'bankoverflow' => false
                ];

                // Si le nombre de point est supérieur au prix de l'objet
                if ($this->getUser()->getPoints() >= $shopItem->getPrice()) {
                    //  alors on peut acheter
                    if ($api->giveItem($data, $character)) {
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
        return $this->render('shop/detail.html.twig', [
            'item' => $item,
            'personnages' => $personnages
        ]);
    }
}
