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
 * CmsPages
 */
#[ORM\Table(name: 'cms_pages')]
class CmsPages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'category', type: 'string', length: 255, nullable: false)]
    private $category;

    /**
     * @var string
     */
    #[ORM\Column(name: 'unique_slug', type: 'string', length: 255, nullable: false)]
    private $uniqueSlug;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'text', length: 65535, nullable: false)]
    private $name;

    /**
     * @var string
     */
    #[ORM\Column(name: 'content', type: 'text', length: 65535, nullable: false)]
    private $content;

    #[ORM\Column(name: 'is_visible', type: 'integer')]
    private $isVisible;


    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return  string
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  string  $category
     *
     * @return  self
     */ 
    public function setCategory(string $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return  string
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param  string  $content
     *
     * @return  self
     */ 
    public function setContent(string $content)
    {
        $this->content = $content;

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
     * Get the value of uniqueSlug
     *
     * @return  string
     */ 
    public function getUniqueSlug()
    {
        return $this->uniqueSlug;
    }

    /**
     * Set the value of uniqueSlug
     *
     * @param  string  $uniqueSlug
     *
     * @return  self
     */ 
    public function setUniqueSlug(string $uniqueSlug)
    {
        $this->uniqueSlug = $uniqueSlug;

        return $this;
    }

    /**
     * Get the value of isVisible
     *
     * @return  bool
     */ 
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * Set the value of isVisible
     *
     * @param  int  $isVisible
     *
     * @return  self
     */ 
    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }
}
