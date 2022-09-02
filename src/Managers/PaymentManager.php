<?php

namespace App\Managers;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class PaymentManager
{
    private $public_key;
    private $private_key;
    private $em;
    private $device;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->public_key = $_ENV["PAYMENT_PUBLIC_KEY"];
        $this->private_key = $_ENV["PAYMENT_PRIVATE_KEY"];
        $this->device = "eur";
        $this->em = $em;
        $this->router = $router;
    }

    public function getPoints()
    {
        return [
            "1" => [
                "name" => "Offre 1",
                "quantity" => 500,
                "price" => 2,
                "image" => "path to file",
                "description" => "Some description here"
            ],
            "2" => [
                "name" => "Offre 2",
                "quantity" => 1000,
                "price" => 4,
                "image" => "path to file",
                "description" => "Some description here"
            ],
            "3" => [
                "name" => "Offre 3",
                "quantity" => 1500,
                "price" => 8,
                "image" => "path to file",
                "description" => "Some description here"
            ],
            "4" => [
                "name" => "Offre 4",
                "quantity" => 2000,
                "price" => 16,
                "image" => "path to file",
                "description" => "Some description here"
            ]
        ];
    }

    public function getPrivateKey(){
        return $this->private_key;
    }

    public function getDevice(){
        return $this->device;
    }

    public function getSign(){
        if($this->device === "eur"){
            return "€";
        }

        if($this->device === "eur"){
            return "€";
        }
    }


}
