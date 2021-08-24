<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsLang
 *
 * @ORM\Table(name="cms_lang")
 * @ORM\Entity
 */
class CmsLang
{
    /**
     * @var int
     *
     * @ORM\Column(name="string_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $stringId;

    /**
     * @var string
     *
     * @ORM\Column(name="str_key", type="string", length=255, nullable=false)
     */
    private $strKey;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=2, nullable=false)
     */
    private $language;


    public function getstrKey()
    {
        return $this->strKey;
    }

    public function getText()
    {
        return $this->text;
    }

     public function getLanguage()
    {
        return $this->language;
    }
}
