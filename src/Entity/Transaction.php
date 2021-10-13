<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TransactionRepository::class")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $walletId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     */
    private $balance_delta;

    /**
     * @ORM\Column(type="float")
     */
    private $available_bal_delta;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    /**
     * @ORM\Column(type="float")
     */
    private $available_balance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ccTransactionId;

    /**
     * @ORM\Column(type="bigint")
     */
    private $ccAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    function __construct($transaction){
        $this->id = $transaction['id'];
        $this->walletId = $transaction['wallet_id'];
        $this->timestamp = (int)$transaction['timestamp'];
        $this->description=$transaction['description'];
        $this->currency=$transaction['currency'];
        $this->balance_delta=$transaction['balance_delta'];
        $this->available_bal_delta=$transaction['available_bal_delta'];
        $this->balance=$transaction['balance'];
        $this->available_bal=$transaction['available_balance'];
        $this->ccTransactionId=$transaction['cc_transaction_id'];
        $this->ccAddress= (int)$transaction['cc_address'];
        $this->value=$transaction['value'];
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWalletId(): ?string
    {
        return $this->walletId;
    }

    public function setWalletId(string $walletId): self
    {
        $this->walletId = $walletId;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getBalanceDelta(): ?float
    {
        return $this->balance_delta;
    }

    public function setBalanceDelta(float $balance_delta): self
    {
        $this->balance_delta = $balance_delta;

        return $this;
    }

    public function getAvailableBalDelta(): ?float
    {
        return $this->available_bal_delta;
    }

    public function setAvailableBalDelta(float $available_bal_delta): self
    {
        $this->available_bal_delta = $available_bal_delta;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getAvailableBalance(): ?float
    {
        return $this->available_balance;
    }

    public function setAvailableBalance(float $available_balance): self
    {
        $this->available_balance = $available_balance;

        return $this;
    }

    public function getCcTransactionId(): ?string
    {
        return $this->ccTransactionId;
    }

    public function setCcTransactionId(string $ccTransactionId): self
    {
        $this->ccTransactionId = $ccTransactionId;

        return $this;
    }

    public function getCcAddress(): ?int
    {
        return $this->ccAddress;
    }

    public function setCcAddress(int $ccAddress): self
    {
        $this->ccAddress = $ccAddress;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
