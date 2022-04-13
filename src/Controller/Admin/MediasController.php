<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/")
 */
class MediasController extends AbstractController
{
    /**
     * @Route("medias", name="admin.medias.home")
     */
    public function index(): Response
    {
        return $this->render('admin/medias/index.html.twig', [
            'controller_name' => 'MediasController',
        ]);
    }
}
