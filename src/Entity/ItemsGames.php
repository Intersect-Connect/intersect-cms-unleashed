<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemsGames
 *
 * @ORM\Table(name="items_games")
 * @ORM\Entity
 */
class ItemsGames
{
    /**
     * @var int
     *
     * @ORM\Column(name="webId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $webid;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=false)
     */
    private $icon;



    /**
     * Get the value of icon
     *
     * @return  string
     */ 
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @param  string  $icon
     *
     * @return  self
     */ 
    public function setIcon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     *
     * @return  self
     */ 
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }
}
