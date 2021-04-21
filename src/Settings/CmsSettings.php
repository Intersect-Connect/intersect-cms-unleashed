<?php

namespace App\Settings;

use App\Entity\CmsPages;
use App\Entity\CmsSettings as EntityCmsSettings;
use Doctrine\ORM\EntityManagerInterface;


class CmsSettings
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGamePage()
    {
        $gamePage = $this->em->getRepository(CmsPages::class)->findBy(['category' => "game", 'isVisible' => 1]);
        return $gamePage;
    }

    public function getWikiPage()
    {
        $gamePage = $this->em->getRepository(CmsPages::class)->findBy(['category' => "wiki", 'isVisible' => 1]);
        return $gamePage;
    }

    public function get($param)
    {
        return $this->em->getRepository(EntityCmsSettings::class)->findOneBy(['setting' => $param])->getDefaultValue();
    }
}
