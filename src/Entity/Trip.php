<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 */
class Trip
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\Column(type="integer")
     */
    private $start_odo;

    /**
     * @ORM\Column(type="integer")
     */
    private $end_odo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $trip_type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getStartOdo(): ?int
    {
        return $this->start_odo;
    }

    public function setStartOdo(int $start_odo): self
    {
        $this->start_odo = $start_odo;

        return $this;
    }

    public function getEndOdo(): ?int
    {
        return $this->end_odo;
    }

    public function setEndOdo(int $end_odo): self
    {
        $this->end_odo = $end_odo;

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

    public function getTripType(): ?bool
    {
        return $this->trip_type;
    }

    public function setTripType(bool $trip_type): self
    {
        $this->trip_type = $trip_type;

        return $this;
    }
}
