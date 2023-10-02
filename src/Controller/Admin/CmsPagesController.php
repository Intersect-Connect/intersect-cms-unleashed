<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller\Admin;

use App\Entity\CmsPages;
use App\Form\CmsPagesType;
use App\Repository\CmsPagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: 'admin/pages')]
class CmsPagesController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings, 
        protected EntityManagerInterface $entityManager,
        protected CmsPagesRepository $cmsPagesRepository
        ){}

    #[Route(path: '/', name: 'cms_pages_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Admin/cms_pages/index.html.twig', [
            'cms_pages' => $this->cmsPagesRepository->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'cms_pages_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $cmsPage = new CmsPages();
        $form = $this->createForm(CmsPagesType::class, $cmsPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cmsPage->setUniqueSlug($this->format_uri($form->get('name')->getData()));
            $this->entityManager->persist($cmsPage);
            $this->entityManager->flush();

            return $this->redirectToRoute('cms_pages_index');
        }

        return $this->render('Admin/cms_pages/new.html.twig', [
            'cms_page' => $cmsPage,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'cms_pages_show', methods: ['GET'])]
    public function show(CmsPages $cmsPage): Response
    {
        return $this->render('Admin/cms_pages/show.html.twig', [
            'cms_page' => $cmsPage,
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'cms_pages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CmsPages $cmsPage, CmsSettings $setting): Response
    {
        $form = $this->createForm(CmsPagesType::class, $cmsPage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cmsPage->setUniqueSlug($this->format_uri($form->get('name')->getData()));
            $this->entityManager->persist($cmsPage);
            $this->entityManager->flush();

            return $this->redirectToRoute('cms_pages_index');
        }

        return $this->render('Admin/cms_pages/edit.html.twig', [
            'cms_page' => $cmsPage,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'cms_pages_delete', methods: ['POST'])]
    public function delete(Request $request, CmsPages $cmsPage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cmsPage->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($cmsPage);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('cms_pages_index');
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
