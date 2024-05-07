<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Settings;

use App\Entity\CmsPages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\CmsSettings as EntityCmsSettings;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class Settings
{

    private bool $isDbReady = false;

    public function __construct(
        protected EntityManagerInterface $em,
        protected ParameterBagInterface  $param
        )
    {
        $filesystem = new Filesystem();
        $dbIsReady = $filesystem->exists($param->get("default_project_path") . 'DB_NOT_READY');
        
        if (!$dbIsReady) {
            $this->isDbReady = false;
        }else{
            $this->isDbReady = true;
        }
    }
    
    /**
     * Get game page
     *
     * @return array<CmsPages>
     */
    public function getGamePage():array
    {
        if(!$this->checkDb()){
            return [];
        }

        $gamePage = $this->em->getRepository(CmsPages::class)->findBy(['category' => "game", 'isVisible' => 1]);
        return $gamePage;
    }

    /**
     * Get wiki page
     *
     * @return array<CmsPages>
     */
    public function getWikiPage():array
    {
        if(!$this->checkDb()){
            return [];
        }

        $gamePage = $this->em->getRepository(CmsPages::class)->findBy(['category' => "wiki", 'isVisible' => 1]);
        return $gamePage;
    }

    /**
     * Get settings
     *
     * @param string $param
     * @return string
     */
    public function get(string $param):string
    {
        if(!$this->checkDb() && $param === "theme"){
            return "BritaniaR";
        }

        if(!$this->checkDb() && $param === "game_title"){
            return "Intersect CMS";
        }

        if(!$this->checkDb() && $param === "seo_description"){
            return "Intersect CMS";
        }

        return $this->em->getRepository(EntityCmsSettings::class)->findOneBy(['setting' => $param])->getDefaultValue();
    }

    public function setSetting(string $param, string $value):void
    {
        if(!$this->checkDb()){
            return;
        }

        $setting = $this->em->getRepository(EntityCmsSettings::class)->findOneBy(["setting" => $param]);

        if($setting){
            $setting->setDefaultValue($value);
            $this->em->persist($setting);
            $this->em->flush();
        }
    }

    private function checkDb(): bool
    {
        $filesystem = new Filesystem();
        $dbIsReady = $filesystem->exists($this->param->get("default_project_path") . 'DB_NOT_READY');
    
        // Pas besoin d'une condition if/else, vous pouvez affecter directement le résultat à $this->isDbReady
        $this->isDbReady = !$dbIsReady;
    
        return $this->isDbReady;
    }
}
