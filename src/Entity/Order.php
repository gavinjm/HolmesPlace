<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $pair;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $volume;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $base_account;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $counter_account;

    /**
     * @ORM\Column(type="boolean")
     */
    private $post_only;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBaseAccount(): ?string
    {
        return $this->base_account;
    }

    public function setBaseAccount(string $base_account): self
    {
        $this->base_account = $base_account;

        return $this;
    }

    public function getCounterAccount(): ?string
    {
        return $this->counter_account;
    }

    public function setCounterAccount(string $counter_account): self
    {
        $this->counter_account = $counter_account;

        return $this;
    }

    public function getPostOnly(): ?bool
    {
        return $this->post_only;
    }

    public function setPostOnly(bool $post_only): self
    {
        $this->post_only = $post_only;

        return $this;
    }
}
