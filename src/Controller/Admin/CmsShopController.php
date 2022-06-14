<?php

namespace App\Controller\Admin;

use App\Entity\CmsShop;
use App\Form\CmsShopType;
use App\Settings\Api;
use App\Settings\CmsSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("shop")
 */
class CmsShopController extends AbstractController
{
    /**
     * @Route("/", name="cms_shop_index", methods={"GET"})
     */
    public function index(CmsSettings $settings): Response
    {
        $cmsShops = $this->getDoctrine()
            ->getRepository(CmsShop::class)
            ->findAll();

        return $this->render('AdminPanel/cms_shop/index.html.twig', [
            'cms_shops' => $cmsShops,
        ]);
    }

    /**
     * @Route("/new", name="cms_shop_new", methods={"GET","POST"})
     */
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
            $objet_detail = $api->getObjectDetail($item_id);
            $cmsShop->setName($objet_detail['Name']);

            // Si la description n'est pas remplis / if description is empty use game description
            if (empty($form->get('forceddescription')->getData())) {
                $cmsShop->setForceddescription($objet_detail['Description']);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsShop);
            $entityManager->flush();

            return $this->redirectToRoute('cms_shop_index');
        }

        return $this->render('AdminPanel/cms_shop/new.html.twig', [
            'cms_shop' => $cmsShop,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="cms_shop_edit", methods={"GET","POST"})
     */
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
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cms_shop_index');
        }

        return $this->render('AdminPanel/cms_shop/edit.html.twig', [
            'cms_shop' => $cmsShop,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cms_shop_delete", methods={"POST"})
     */
    public function delete(Request $request, CmsShop $cmsShop): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cmsShop->getId(), $request->request->get('_token'))) {
            $nom = $cmsShop->getImage();
            unlink($this->getParameter('images_items') . '/' . $nom);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cmsShop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cms_shop_index');
    }
}
