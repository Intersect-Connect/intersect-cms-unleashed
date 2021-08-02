<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class UpdaterController extends AbstractController
{
    /**
     * @Route("/updater", name="updater")
     */
    public function index(): Response
    {
        $path = $this->getParameter('updater_folder');

        $obj = (object) [];
        $obj->TrustCache = true;
        $obj->Files = [];
        $this->scanD($path, $obj);

        // return $this->json($obj);
        // echo new JsonEncoder(new JsonEncode((array) $obj, JSON_UNESCAPED_SLASHES));
        echo json_encode($obj, JSON_UNESCAPED_SLASHES);


        return new JsonResponse((array) $obj, 200, [], false);
    }


    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    function scanD($target, $obj)
    {

        $excludeFiles = array("resources/mapcache.db", "update.json", "version.json");
        $clientExcludeFiles = array("Intersect Editor.exe", "Intersect Editor.pdb");
        $excludeDirectories = array("logs", "screenshots");
        $excludeExtensions = array(".xml", ".config", ".php");

        if (is_dir($target)) {

            $skipDir = false;
            $dir = str_replace(getcwd() . "/", "", $target);
            foreach ($excludeDirectories as $excludeDir) {
                if ($this->endsWith($dir, $excludeDir . "/")) {
                    $skipDir = true;
                    break;
                }
            }

            if ($skipDir == false) {
                $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

                foreach ($files as $file) {
                    $this->scanD($file, $obj);

                    $skip = is_dir($file);

                    foreach ($excludeExtensions as $extension) {
                        if ($this->endsWith($file, $extension)) {
                            $skip = true;
                            break;
                        }
                    }

                    $path = str_replace(getcwd() . "/", "", $file);

                    if (in_array($path, $excludeFiles)) {
                        $skip = true;
                    }

                    if ($skip == false) {
                        if (in_array($path, $clientExcludeFiles)) {
                            $obj->Files[] = array("Path" => $path, "Hash" => md5_file($file), "Size" => filesize($file), "ClientIgnore" => true);
                        } else {
                            $obj->Files[] = array("Path" => $path, "Hash" => md5_file($file), "Size" => filesize($file));
                        }
                    }
                }
            }
        }
    }
}
