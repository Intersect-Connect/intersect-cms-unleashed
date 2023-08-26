<?php

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Settings\CmsSettings;
use App\Repository\CmsSettingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/character")
 * @IsGranted("ROLE_ADMIN")
 */
class CharacterController extends AbstractController
{
    /**
     * @Route("/detail/{character}", name="admin.character.detail")
     */
    public function characterDetail(Api $api, CmsSettingsRepository $settings, Request $request, TranslatorInterface $translator, $character, CmsSettings $setting): Response
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
                if ($api->giveItem($data, $character)) {
                    $this->addFlash('success', $translator->trans('L\'opération s\'est bien passé.'));
                    return $this->redirectToRoute('admin.character.detail', ['character' => $character]);
                }
            }

            if ($action == "add") {
                $data = [
                    'itemid' => $id,
                    'quantity' => $quantity
                ];

                if ($api->giveItem($data, $character)) {
                    $this->addFlash('success', $translator->trans('L\'opération s\'est bien passé.'));
                    return $this->redirectToRoute('admin.character.detail', ['character' => $character]);
                }
            }

            if ($action == "del") {
                $data = [
                    'itemid' => $id,
                    'quantity' => $quantity
                ];

                if ($api->takeItem($data, $character)) {
                    $this->addFlash('success', $translator->trans('L\'opération s\'est bien passé.'));
                    return $this->redirectToRoute('admin.character.detail', ['character' => $character]);
                }
            }
        }

        $inventory = $api->getInventory($character);
        $inventory_list = [];

        $bank = $api->getBank($character);
        $bank_list = [];
        $bag_list = [];

        foreach ($inventory as $item) {
            if ($item['ItemId'] != "00000000-0000-0000-0000-000000000000") {
                $object = $api->getObjectDetail($item['ItemId']);
                if ($item['BagId'] != null) {
                    $bag_items = $api->getBag($item['BagId']);

                    foreach ($bag_items['Slots'] as $item) {
                        if ($item['ItemId'] != "00000000-0000-0000-0000-000000000000") {
                            $object = $api->getObjectDetail($item['ItemId']);

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
                $object = $api->getObjectDetail($item['ItemId']);

                $bank_list[] = [
                    'id' => $item['ItemId'],
                    'name' => $object['Name'],
                    'icon' => $object['Icon'],
                    'quantity' => $item['Quantity']
                ];
            }
        }

        return $this->render($setting->get('theme') . '/admin/account/character.html.twig', [
            'player' => $api->getCharacter($character),
            'inventory' => $inventory_list,
            'bank' => $bank_list,
            'bag' => $bag_list
        ]);
    }
}
