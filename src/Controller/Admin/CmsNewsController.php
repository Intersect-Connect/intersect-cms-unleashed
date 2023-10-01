<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Controller\Admin;

use DateTime;
use App\Settings\Api;
use App\Entity\CmsNews;
use App\Form\CmsNewsType;
use App\Repository\CmsNewsRepository;
use Symfony\Component\Asset\Packages;
use Doctrine\ORM\EntityManagerInterface;
use App\Settings\Settings as CmsSettings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: 'admin/news/')]
class CmsNewsController extends AbstractController
{
    public function __construct(
        protected CmsSettings $settings,
        protected Api $api,
        protected EntityManagerInterface $entityManager,
        protected CmsNewsRepository $cmsNewsRepository
    ) {
    }

    #[Route(path: '/', name: 'cms_news_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render($this->settings->get('theme') . '/admin/cms_news/index.html.twig', [
            'cms_news' => $this->cmsNewsRepository->findAll(),
        ]);
    }

    #[Route(path: '/new', name: 'cms_news_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $cmsNews = new CmsNews();
        $form = $this->createForm(CmsNewsType::class, $cmsNews);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichier = $form->get('imgUrl')->getData();

            if ($fichier != null) {
                $fichierNew = md5(uniqid()) . '.' . $fichier->guessExtension();

                // On copie le fichier dans le dossier uploads
                $fichier->move(
                    $this->getParameter('images_articles'),
                    $fichierNew
                );
                $cmsNews->setImgUrl($fichierNew);
            }

            $cmsNews->setDate(new DateTime());
            $cmsNews->setAuthor($this->getUser()->getUsername());
            $cmsNews->setSlug($this->format_uri($form->get('title')->getData()));

            $this->entityManager->persist($cmsNews);
            $this->entityManager->flush();

            return $this->redirectToRoute('cms_news_index');
        }

        return $this->render($this->settings->get('theme') . '/admin/cms_news/new.html.twig', [
            'cms_news' => $cmsNews,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}/edit', name: 'cms_news_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CmsNews $cmsNews): Response
    {
        $image = $cmsNews->getImgUrl();
        $form = $this->createForm(CmsNewsType::class, $cmsNews);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($image) {
                $fichier = $form->get('imgUrl')->getData();

                if ($fichier != null) {
                    $fichierNew = md5(uniqid()) . '.' . $fichier->guessExtension();

                    // On copie le fichier dans le dossier uploads
                    $fichier->move(
                        $this->getParameter('images_articles'),
                        $fichierNew
                    );
                    $cmsNews->setImgUrl($fichierNew);
                } else {
                    $cmsNews->setImgUrl($image);
                }
            } else {
                $cmsNews->setImgUrl($image);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('cms_news_index');
        }

        return $this->render($this->settings->get('theme') . '/admin/cms_news/edit.html.twig', [
            'cms_news' => $cmsNews,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/{id}', name: 'cms_news_delete', methods: ['POST'])]
    public function delete(Request $request, CmsNews $cmsNews): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cmsNews->getId(), $request->request->get('_token'))) {
            $nom = $cmsNews->getImgUrl();
            unlink($this->getParameter('images_articles') . '/' . $nom);
            $this->entityManager->remove($cmsNews);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('cms_news_index');
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
