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
use App\Entity\CmsSettings as EntityCmsSettings;
use Doctrine\ORM\EntityManagerInterface;


class Settings
{


    public function __construct(
        protected EntityManagerInterface $em)
    {}
    
    /**
     * Get game page
     *
     * @return array<CmsPages>
     */
    public function getGamePage():array
    {
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
        return $this->em->getRepository(EntityCmsSettings::class)->findOneBy(['setting' => $param])->getDefaultValue();
    }
}
