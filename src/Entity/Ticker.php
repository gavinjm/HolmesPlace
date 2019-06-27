<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TickerRepository")
 */
class Ticker
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $pair;

    /**
     * @ORM\Column(type="integer")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="float")
     */
    private $bid;

    /**
     * @ORM\Column(type="float")
     */
    private $ask;

    /**
     * @ORM\Column(type="float")
     */
    private $last_trade;

    /**
     * @ORM\Column(type="float")
     */
    private $rolling_24_hour_volume;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPair(): ?string
    {
        return $this->pair;
    }

    public function setPair(string $pair): self
    {
        $this->pair = $pair;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getBid(): ?float
    {
        return $this->bid;
    }

    public function setBid(float $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getAsk(): ?float
    {
        return $this->ask;
    }

    public function setAsk(float $ask): self
    {
        $this->ask = $ask;

        return $this;
    }

    public function getLastTrade(): ?float
    {
        return $this->last_trade;
    }

    public function setLastTrade(float $last_trade): self
    {
        $this->last_trade = $last_trade;

        return $this;
    }

    public function getRolling24HourVolume(): ?float
    {
        return $this->rolling_24_hour_volume;
    }

    public function setRolling24HourVolume(float $rolling_24_hour_volume): self
    {
        $this->rolling_24_hour_volume = $rolling_24_hour_volume;

        return $this;
    }
}
