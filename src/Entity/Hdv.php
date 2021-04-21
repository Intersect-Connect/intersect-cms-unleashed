<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hdv
 *
 * @ORM\Table(name="hdv")
 * @ORM\Entity
 */
class Hdv
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
     * @var string
     *
     * @ORM\Column(name="player_username", type="string", length=255, nullable=false)
     */
    private $playerUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="player_id", type="string", length=255, nullable=false)
     */
    private $playerId;

    /**
     * @var string
     *
     * @ORM\Column(name="item_id", type="string", length=255, nullable=false)
     */
    private $itemId;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;



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
     * Get the value of playerUsername
     *
     * @return  string
     */ 
    public function getPlayerUsername()
    {
        return $this->playerUsername;
    }

    /**
     * Set the value of playerUsername
     *
     * @param  string  $playerUsername
     *
     * @return  self
     */ 
    public function setPlayerUsername(string $playerUsername)
    {
        $this->playerUsername = $playerUsername;

        return $this;
    }

    /**
     * Get the value of playerId
     *
     * @return  string
     */ 
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set the value of playerId
     *
     * @param  string  $playerId
     *
     * @return  self
     */ 
    public function setPlayerId(string $playerId)
    {
        $this->playerId = $playerId;

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
}
