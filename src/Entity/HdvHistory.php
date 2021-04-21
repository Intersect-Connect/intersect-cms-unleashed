<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HdvHistory
 *
 * @ORM\Table(name="hdv_history")
 * @ORM\Entity
 */
class HdvHistory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="item_id", type="string", length=255, nullable=false)
     */
    private $itemId;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=255, nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="sold_by", type="string", length=255, nullable=false)
     */
    private $soldBy;



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
     * Get the value of date
     *
     * @return  \DateTime
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param  \DateTime  $date
     *
     * @return  self
     */ 
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of itemId
     *
     * @return  string
     */ 
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set the value of itemId
     *
     * @param  string  $itemId
     *
     * @return  self
     */ 
    public function setItemId(string $itemId)
    {
        $this->itemId = $itemId;

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
     * Get the value of userId
     *
     * @return  string
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @param  string  $userId
     *
     * @return  self
     */ 
    public function setUserId(string $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of soldBy
     *
     * @return  string
     */ 
    public function getSoldBy()
    {
        return $this->soldBy;
    }

    /**
     * Set the value of soldBy
     *
     * @param  string  $soldBy
     *
     * @return  self
     */ 
    public function setSoldBy(string $soldBy)
    {
        $this->soldBy = $soldBy;

        return $this;
    }
}
