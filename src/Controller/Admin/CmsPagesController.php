<?php

namespace App\Controller\Admin;

use App\Entity\CmsPages;
use App\Form\CmsPagesType;
use App\Settings\CmsSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("admin/pages", name="admin.pages.")
 */
class CmsPagesController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CmsSettings $settings): Response
    {
        $cmsPages = $this->getDoctrine()
            ->getRepository(CmsPages::class)
            ->findAll();

        return $this->render('AdminPanel/cms_pages/index.html.twig', [
            'cms_pages' => $cmsPages,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, CmsSettings $settings): Response
    {
        $cmsPage = new CmsPages();
        $form = $this->createForm(CmsPagesType::class, $cmsPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cmsPage->setUniqueSlug($this->format_uri($form->get('name')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsPage);
            $entityManager->flush();

            return $this->redirectToRoute('admin.pages.index');
        }

        return $this->render('AdminPanel/cms_pages/new.html.twig', [
            'cms_page' => $cmsPage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(CmsPages $cmsPage, CmsSettings $setting): Response
    {
        return $this->render('AdminPanel/cms_pages/show.html.twig', [
            'cms_page' => $cmsPage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CmsPages $cmsPage, CmsSettings $setting): Response
    {
        $form = $this->createForm(CmsPagesType::class, $cmsPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($cmsPage->getCategory() === "Legal"){
                $cmsPage->setCategory("Legal");
                $cmsPage->setUniqueSlug($cmsPage->getUniqueSlug());
            }else{
                $cmsPage->setUniqueSlug($this->format_uri($form->get('name')->getData()));
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cmsPage);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.pages.index');
        }

        return $this->render('AdminPanel/cms_pages/edit.html.twig', [
            'cms_page' => $cmsPage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, CmsPages $cmsPage, CmsSettings $setting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsPage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cmsPage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.pages.index');
    }

        public function format_uri($string, $separator = '-')
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array('&' => 'and', "'" => '');
        $string = mb_strtolower(trim($string), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }
}
