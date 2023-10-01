<?php

namespace App\Controller\Admin;

use App\Settings\Api;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CmsSettingsRepository;
use App\Settings\Settings as CmsSettings;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: 'admin/character')]
class CharacterController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings, 
        protected Api $api, 
        protected CacheInterface $cache, 
        protected PaginatorInterface $paginator,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator
        ){}
        
    #[Route(path: '/detail/{character}', name: 'admin.character.detail')]
    public function characterDetail(Request $request, string $character): Response
    {
        if ($request->isMethod('POST')) {
            $id = $request->request->get('item');
            $quantity = $request->request->get('quantity');
            $action = $request->request->get('action');

            if ($action == "give") {

                $data = [
                    'itemid' => $id,
                    'quantity' => $quantity
                ];
                if ($this->api->giveItem($data, $character)) {
                    $this->addFlash('success', $this->translator->trans('L\'opération s\'est bien passé.'));
                    return $this->redirectToRoute('admin.character.detail', ['character' => $character]);
                }
            }

            if ($action == "add") {
                $data = [
                    'itemid' => $id,
                    'quantity' => $quantity
                ];

                if ($this->api->giveItem($data, $character)) {
                    $this->addFlash('success', $this->translator->trans('L\'opération s\'est bien passé.'));
                    return $this->redirectToRoute('admin.character.detail', ['character' => $character]);
                }
            }

            if ($action == "del") {
                $data = [
                    'itemid' => $id,
                    'quantity' => $quantity
                ];

                if ($this->api->takeItem($data, $character)) {
                    $this->addFlash('success', $this->translator->trans('L\'opération s\'est bien passé.'));
                    return $this->redirectToRoute('admin.character.detail', ['character' => $character]);
                }
            }
        }

        $inventory = $this->api->getInventory($character);
        $inventory_list = [];

        $bank = $this->api->getBank($character);
        $bank_list = [];
        $bag_list = [];

        foreach ($inventory as $item) {
            if ($item['ItemId'] != "00000000-0000-0000-0000-000000000000") {
                $object = $this->api->getObjectDetail($item['ItemId']);
                if ($item['BagId'] != null) {
                    $bag_items = $this->api->getBag($item['BagId']);

                    foreach ($bag_items['Slots'] as $item) {
                        if ($item['ItemId'] != "00000000-0000-0000-0000-000000000000") {
                            $object = $this->api->getObjectDetail($item['ItemId']);

                            $bag_list[] = [
                                'id' => $item['ItemId'],
                                'name' => $object['Name'],
                                'icon' => $object['Icon'],
                                'quantity' => $item['Quantity']
                            ];
                        }
                    }
                }
                $inventory_list[] = [
                    'id' => $item['ItemId'],
                    'name' => $object['Name'],
                    'icon' => $object['Icon'],
                    'quantity' => $item['Quantity']
                ];
            }
        }

        foreach ($bank as $item) {
            if ($item['ItemId'] != "00000000-0000-0000-0000-000000000000") {
                $object = $this->api->getObjectDetail($item['ItemId']);

                $bank_list[] = [
                    'id' => $item['ItemId'],
                    'name' => $object['Name'],
                    'icon' => $object['Icon'],
                    'quantity' => $item['Quantity']
                ];
            }
        }

        return $this->render($this->settings->get('theme') . '/admin/account/character.html.twig', [
            'player' => $this->api->getCharacter($character),
            'inventory' => $inventory_list,
            'bank' => $bank_list,
            'bag' => $bag_list
        ]);
    }
}
