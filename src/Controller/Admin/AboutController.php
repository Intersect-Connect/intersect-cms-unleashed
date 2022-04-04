<?php

namespace App\Controller\Admin;

use ZipArchive;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    /**
     * @Route("/admin/about", name="about.home")
     */
    public function index(): Response
    {
        $getLocalVersion = file_get_contents($this->getParameter('version'));
        $versionLocal = json_decode($getLocalVersion)->version;

        $getOnlineVersion = file_get_contents("https://www.dropbox.com/s/tcyg3mi6i74jgkl/update.json?dl=1");
        $versionOnline = json_decode($getOnlineVersion);
        $updateAvailable = false;
        // dd($versionOnline);

        if ($versionLocal < $versionOnline->version) {
            $updateAvailable = true;
        } else {
            if ($versionLocal === $versionOnline->version) {
                $updateAvailable = false;
            }
        }



        return $this->render('AdminPanel/about/index.html.twig', [
            "versionLocal" => $versionLocal,
            "updateAvailable" => $updateAvailable
        ]);
    }


    /**
     * @Route("/admin/updateCheck", name="update.check")
     */
    public function checkUpdate()
    {
        $getLocalVersion = file_get_contents($this->getParameter('version'));
        $versionLocal = json_decode($getLocalVersion)->version;

        $getOnlineVersion = file_get_contents("https://www.dropbox.com/s/tcyg3mi6i74jgkl/update.json?dl=1");
        $versionOnline = json_decode($getOnlineVersion);
        $updateAvailable = false;

        if ($versionLocal < $versionOnline->version) {
            $updateAvailable = true;
        } else {
            if ($versionLocal === $versionOnline->version) {
                $updateAvailable = false;
            }
        }

        return new JsonResponse($updateAvailable);
    }

    /**
     * @Route("/admin/updateDownload", name="update.download")
     */
    public function downloadUpdate()
    {
        $getOnlineVersion = file_get_contents("https://www.dropbox.com/s/tcyg3mi6i74jgkl/update.json?dl=1");
        $versionOnline = json_decode($getOnlineVersion);
        
        // get latest german WordPress file
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $versionOnline->downloadLink);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        // save as wordpress.zip
        $destination = $this->getParameter("update") . "cmsupdate.zip";
        $file = fopen($destination, "w+");
        fputs($file, $data);
        fclose($file);

        // unzip
        $zip = new ZipArchive;
        $res = $zip->open($this->getParameter("update") . "cmsupdate.zip");
        if ($res === TRUE) {
            $zip->extractTo($this->getParameter("update") . '/test'); // directory to extract contents to
            $zip->close();
            // unlink('cmsupdate.zip');
            $this->addFlash('success', 'Update installed');
            return new JsonResponse(true);
        } else {
            $this->addFlash('error', 'Update failed');
            return new JsonResponse(false);
        }
    }
}
