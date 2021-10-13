<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CryptoPricesRepository")
 */
class CryptoPrices
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $btc_price;
    
    /**
     * @ORM\Column(type="float")
     */
    private $eth_price;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date; 

        return $this;
    }

   
    public function getBtcPrice(): ?float
    {
        return $this->btc_price;
    }

    public function setBtcPrice(float $price): self
    {
        $this->btc_price = $price;

        return $this;
    }
    public function getEthPrice(): ?float
    {
        return $this->eth_price;
    }

    public function setEthPrice(float $price): self
    {
        $this->eth_price = $price;

        return $this;
    }
}
