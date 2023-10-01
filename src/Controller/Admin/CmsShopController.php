<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller\Admin;

use App\Settings\Api;
use App\Entity\CmsShop;
use App\Form\CmsShopType;
use App\Repository\CmsShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: 'admin/shop')]
class CmsShopController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings,
        protected Api $api,
        protected CacheInterface $cache,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        protected CmsShopRepository $cmsShopRepository
    ) {
    }

    #[Route(path: '/', name: 'cms_shop_index', methods: ['GET'])]
    public function index(CmsSettings $settings): Response
    {
        return $this->render($this->settings->get('theme') . '/admin/cms_shop/index.html.twig', [
            'cms_shops' => $this->cmsShopRepository->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'cms_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Api $api, CmsSettings $settings): Response
    {
        $cmsShop = new CmsShop();
        $form = $this->createForm(CmsShopType::class, $cmsShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image != null) {
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_items'),
                    $fichier
                );
                $cmsShop->setImage($fichier);
            } else {
                $cmsShop->setImage(null);
            }



            $item_id = $form->get('idItem')->getData();
            $objet_detail = $this->api->getObjectDetail($item_id);
            $cmsShop->setName($objet_detail['Name']);

            // Si la description n'est pas remplis / if description is empty use game description
            if (empty($form->get('forceddescription')->getData())) {
                $cmsShop->setForceddescription($objet_detail['Description']);
            }

            $this->entityManager->persist($cmsShop);
            $this->entityManager->flush();

            return $this->redirectToRoute('cms_shop_index');
        }

        return $this->render($this->settings->get('theme') . '/admin/cms_shop/new.html.twig', [
            'cms_shop' => $cmsShop,
            'form' => $form->createView(),
        ]);
    }


    #[Route(path: '/{id}/edit', name: 'cms_shop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CmsShop $cmsShop, CmsSettings $settings): Response
    {
        $form = $this->createForm(CmsShopType::class, $cmsShop);
        $form->handleRequest($request);
        $image = $cmsShop->getImage();

        if ($form->isSubmitted() && $form->isValid()) {
            $newImage =  $form->get('image')->getData();
            if ($newImage) {
                $fichier = md5(uniqid()) . '.' . $newImage->guessExtension();
                $newImage->move(
                    $this->getParameter('images_items'),
                    $fichier
                );
                $cmsShop->setImage($fichier);
            } else {
                if ($image) {
                    $cmsShop->setImage($image);
                } else {
                    $cmsShop->setImage(null);
                }
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('cms_shop_index');
        }

        return $this->render($this->settings->get('theme') . '/admin/cms_shop/edit.html.twig', [
            'cms_shop' => $cmsShop,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'cms_shop_delete', methods: ['POST'])]
    public function delete(Request $request, CmsShop $cmsShop): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cmsShop->getId(), $request->request->get('_token'))) {
            $nom = $cmsShop->getImage();
            unlink($this->getParameter('images_items') . '/' . $nom);

            $this->entityManager->remove($cmsShop);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('cms_shop_index');
    }
}
