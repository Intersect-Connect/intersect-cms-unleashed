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
 * CmsPointsHistory
 *
 * @ORM\Table(name="cms_points_history")
 * @ORM\Entity
 */
class CmsPointsHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     * @ORM\Column(name="user_id", type="text", length=65535, nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="points_amount", type="integer", nullable=false)
     */
    private $pointsAmount;



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
     * Get the value of code
     *
     * @return  string
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @param  string  $code
     *
     * @return  self
     */ 
    public function setCode(string $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of pointsAmount
     *
     * @return  int
     */ 
    public function getPointsAmount()
    {
        return $this->pointsAmount;
    }

    /**
     * Set the value of pointsAmount
     *
     * @param  int  $pointsAmount
     *
     * @return  self
     */ 
    public function setPointsAmount(int $pointsAmount)
    {
        $this->pointsAmount = $pointsAmount;

        return $this;
    }
}
