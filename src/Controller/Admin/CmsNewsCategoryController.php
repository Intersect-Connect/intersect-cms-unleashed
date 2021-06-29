<?php

namespace App\Controller\Admin;

use App\Entity\CmsNewsCategory;
use App\Repository\CmsNewsCategoryRepository;
use App\Settings\CmsSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("admin/news/category")
 */

class CmsNewsCategoryController extends AbstractController
{
    /**
     * @Route("/", name="cms_news_category")
     */
    public function index(CmsSettings $setting, CmsNewsCategoryRepository $categoryRepo): Response
    {
        return $this->render($setting->get('theme') . '/admin/cms_news_category/index.html.twig', [
            'categorys' => $categoryRepo->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cms_news_category.new")
     */
    public function new(CmsSettings $setting, Request $request, TranslatorInterface $translator): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $name = $request->request->get('name');
            $color = $request->request->get('color');

            if (isset($name) && !empty($name) && isset($color) && !empty($color)) {

                

                $newCategory = new CmsNewsCategory();
                $newCategory->setName($name);
                $entityManager->persist($newCategory);
                $entityManager->flush();
                $this->addFlash('success', $translator->trans('Votre catégorie à bien été enregistré.'));
                return $this->redirectToRoute('cms_news_category');
            }
        }
        return $this->render($setting->get('theme') . '/admin/cms_news_category/new.html.twig', []);
    }

    /**
     * @Route("/edit/{id}", name="cms_news_category.edit")
     */
    public function edit(CmsSettings $setting, Request $request, TranslatorInterface $translator, CmsNewsCategory $category): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $name = $request->request->get('name');
            $color = $request->request->get('color');

            if (isset($name) && !empty($name) && isset($color) && !empty($color)) {
                $category->setName($name);
                $category->setColor($color);
                $entityManager->persist($category);
                $entityManager->flush();
                $this->addFlash('success', $translator->trans('Votre catégorie à bien été enregistré.'));
                return $this->redirectToRoute('cms_news_category');
            }
        }
        return $this->render($setting->get('theme') . '/admin/cms_news_category/edit.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete/{id}", name="cms_news_category.delete")
     */
    public function delete(CmsSettings $setting, Request $request, TranslatorInterface $translator, CmsNewsCategory $category): Response
    {
        if ($category) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('Votre catégorie à bien été enregistré.'));
            return $this->redirectToRoute('cms_news_category');
        }
    }
}
