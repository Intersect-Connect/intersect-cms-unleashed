<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsShop
 *
 * @ORM\Table(name="cms_shop")
 * @ORM\Entity
 */
class CmsShop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="id_item", type="string", length=255, nullable=false)
     */
    private $idItem;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="forceddescription", type="text", length=65535, nullable=false)
     */
    private $forceddescription;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false, options={"default"="1"})
     */
    private $quantity = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="promotion", type="integer", nullable=false)
     */
    private $promotion = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="visible", type="integer", nullable=false, options={"default"="1"})
     */
    private $visible = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;



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
     * Get the value of idItem
     *
     * @return  string
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set the value of idItem
     *
     * @param  string  $idItem
     *
     * @return  self
     */
    public function setIdItem(string $idItem)
    {
        $this->idItem = $idItem;

        return $this;
    }

    /**
     * Get the value of price
     *
     * @return  int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param  int  $price
     *
     * @return  self
     */
    public function setPrice(int $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of forceddescription
     *
     * @return  string
     */
    public function getForceddescription()
    {
        return $this->forceddescription;
    }

    /**
     * Set the value of forceddescription
     *
     * @param  string  $forceddescription
     *
     * @return  self
     */
    public function setForceddescription(string $forceddescription)
    {
        $this->forceddescription = $forceddescription;

        return $this;
    }

    /**
     * Get the value of quantity
     *
     * @return  int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @param  int  $quantity
     *
     * @return  self
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of promotion
     *
     * @return  int
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set the value of promotion
     *
     * @param  int  $promotion
     *
     * @return  self
     */
    public function setPromotion(int $promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get the value of visible
     *
     * @return  bool
     */
    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    /**
     * Set the value of visible
     *
     * @param  bool  $visible
     *
     * @return  self
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;

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
     * Get the value of image
     *
     * @return  string
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param  string  $image
     *
     * @return  self
     */ 
    public function setImage(?string $image)
    {
        $this->image = $image;

        return $this;
    }
}
