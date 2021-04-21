<?php

namespace App\Controller\Admin;

use App\Entity\CmsShop;
use App\Form\CmsShopType;
use App\Settings\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/shop")
 */
class CmsShopController extends AbstractController
{
    /**
     * @Route("/", name="cms_shop_index", methods={"GET"})
     */
    public function index(): Response
    {
        $cmsShops = $this->getDoctrine()
            ->getRepository(CmsShop::class)
            ->findAll();

        return $this->render('admin/cms_shop/index.html.twig', [
            'cms_shops' => $cmsShops,
        ]);
    }

    /**
     * @Route("/new", name="cms_shop_new", methods={"GET","POST"})
     */
    public function new(Request $request, Api $api): Response
    {
        $cmsShop = new CmsShop();
        $form = $this->createForm(CmsShopType::class, $cmsShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item_id = $form->get('idItem')->getData();
            $objet_detail = $api->getObjectDetail($item_id);
            $cmsShop->setName($objet_detail['Name']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsShop);
            $entityManager->flush();

            return $this->redirectToRoute('cms_shop_index');
        }

        return $this->render('admin/cms_shop/new.html.twig', [
            'cms_shop' => $cmsShop,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="cms_shop_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CmsShop $cmsShop): Response
    {
        $form = $this->createForm(CmsShopType::class, $cmsShop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cms_shop_index');
        }

        return $this->render('admin/cms_shop/edit.html.twig', [
            'cms_shop' => $cmsShop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cms_shop_delete", methods={"POST"})
     */
    public function delete(Request $request, CmsShop $cmsShop): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsShop->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cmsShop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cms_shop_index');
    }
}
