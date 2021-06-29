<?php

namespace App\Entity;

use App\Repository\CmsNewsCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cms_news_category")
 * @ORM\Entity(repositoryClass=CmsNewsCategoryRepository::class)
 */
class CmsNewsCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CmsNews::class, mappedBy="category")
     */
    private $cmsNews;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    public function __construct()
    {
        $this->cmsNews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|CmsNews[]
     */
    public function getCmsNews(): Collection
    {
        return $this->cmsNews;
    }

    public function addCmsNews(CmsNews $cmsNews): self
    {
        if (!$this->cmsNews->contains($cmsNews)) {
            $this->cmsNews[] = $cmsNews;
            $cmsNews->setCategory($this);
        }

        return $this;
    }

    public function removeCmsNews(CmsNews $cmsNews): self
    {
        if ($this->cmsNews->removeElement($cmsNews)) {
            // set the owning side to null (unless already changed)
            if ($cmsNews->getCategory() === $this) {
                $cmsNews->setCategory(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
