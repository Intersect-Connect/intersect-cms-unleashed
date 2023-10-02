<?php

/**
 * Intersect CMS Unleashed
 * 2.4 : PHP 8.x Update
 * Last modify : 02/10/2023
 * Author : XFallSeane
 * Website : https://intersect-connect.tk
 */

namespace App\Controller\Admin;

use App\Entity\CmsNewsCategory;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use App\Repository\CmsNewsCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: 'admin/news/category')]
class CmsNewsCategoryController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings,
        protected EntityManagerInterface $entityManager,
        protected CmsNewsCategoryRepository $cmsNewsCategoryRepository,
        protected TranslatorInterface $translator
    ) {
    }

    #[Route(path: '/', name: 'cms_news_category')]
    public function index(CmsNewsCategoryRepository $categoryRepo): Response
    {
        return $this->render('Admin/cms_news_category/index.html.twig', [
            'categorys' => $categoryRepo->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'cms_news_category.new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $color = $request->request->get('color');

            if (isset($name) && !empty($name) && isset($color) && !empty($color)) {
                $newCategory = new CmsNewsCategory();
                $newCategory->setName($name);
                $newCategory->setColor($color);
                $this->entityManager->persist($newCategory);
                $this->entityManager->flush();
                $this->addFlash('success', $this->translator->trans('Votre catégorie à bien été enregistré.'));
                return $this->redirectToRoute('cms_news_category');
            }
        }
        return $this->render('Admin/cms_news_category/new.html.twig', []);
    }

    #[Route(path: '/edit/{id}', name: 'cms_news_category.edit')]
    public function edit(Request $request, CmsNewsCategory $category): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $color = $request->request->get('color');

            if (isset($name) && !empty($name) && isset($color) && !empty($color)) {
                $category->setName($name);
                $category->setColor($color);
                $this->entityManager->persist($category);
                $this->entityManager->flush();
                $this->addFlash('success', $this->translator->trans('Votre catégorie à bien été enregistré.'));
                return $this->redirectToRoute('cms_news_category');
            }
        }
        return $this->render('Admin/cms_news_category/edit.html.twig', [
            'category' => $category
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'cms_news_category.delete')]
    public function delete(CmsNewsCategory $category): Response
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
        $this->addFlash('success', $this->translator->trans('Votre catégorie à bien été enregistré.'));
        return $this->redirectToRoute('cms_news_category');
    }
}
