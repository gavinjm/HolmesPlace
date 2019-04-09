<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CryptoTransactionRepository")
 */
class CryptoTransaction
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
     * @ORM\Column(type="string", length=5)
     */
    private $action;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $wallet;

    /**
     * @ORM\Column(type="float")
     */
    private $amount_zar;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount_btc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount_eth;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getWallet(): ?string
    {
        return $this->wallet;
    }

    public function setWallet(string $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getAmountZar(): ?float
    {
        return $this->amount_zar;
    }

    public function setAmountZar(float $amount_zar): self
    {
        $this->amount_zar = $amount_zar;

        return $this;
    }

    public function getAmountBtc(): ?float
    {
        return $this->amount_btc;
    }

    public function setAmountBtc(?float $amount_btc): self
    {
        $this->amount_btc = $amount_btc;

        return $this;
    }

    public function getAmountEth(): ?float
    {
        return $this->amount_eth;
    }

    public function setAmountEth(?float $amount_eth): self
    {
        $this->amount_eth = $amount_eth;

        return $this;
    }
}
