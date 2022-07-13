<?php

namespace App\Services;

use DirectoryIterator;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class CheckPlugins
{
    private $params;
    private $em;
    private $router;
    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->params = $params;
        $this->em = $em;
        $this->router = $router;
    }
    
    /**
     * @param string $controller
     * @return bool
     */
    public function has($pluginName)
    {
        return is_dir($this->params->get('plugins_path') . "/" . $pluginName);
    }

    public function checkTableDatabase($table)
    {
        try {
            $sql = "SELECT * FROM $table";
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
            $stmt->fetchAll();
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
        return true;
    }

    public function install($sqlData){
        try {
            $sql = $sqlData;
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
        return true;
    }

    public function isEmpty()
    {
        $di = new RecursiveDirectoryIterator($this->params->get('plugins_path'), FilesystemIterator::SKIP_DOTS);
        return iterator_count($di) === 0;
    }

    public function getAllPluginsFolder()
    {
        $dir = new DirectoryIterator($this->params->get('plugins_path'));
        $allDir = [];
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $jsonData = json_decode(file_get_contents($fileinfo->getPathname() . "/info.json"));
                
                $allDir[] = [
                    "pluginName" => $jsonData->name,
                    "folderName" => $fileinfo->getFilename(),
                    "folderPath" => $fileinfo->getPathname(),
                    "paths" => $jsonData->paths
                ];
            }
        }
        return $allDir;
    }

    public function findRoute($pluginName){
        $paths = [];

        foreach($this->router->getRouteCollection() as $key => $route){
            if(strpos($key, $pluginName) !== false){
                return true;
            }
        }

        return false;
    }
}
