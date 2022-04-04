<?php

namespace App\Controller\Admin;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    /**
     * @Route("/admin/about", name="admin_about")
     */
    public function index(): Response
    {
        $getVersion = file_get_contents($this->getParameter('version'));
        $version = json_decode($getVersion)->version;
        
        return $this->render('AdminPanel/about/index.html.twig', [
            "version" => $version
        ]);
    }
}
