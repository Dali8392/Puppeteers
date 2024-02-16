<?php

namespace App\Entity;

use App\Repository\ReservationHebergementRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationHebergementRepository::class)]
class ReservationHebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $idUser = null;

    #[ORM\Column]
    private ?DateTime $date = null;

    #[ORM\Column]
    private ?DateTime $duree = null;

    #[ORM\Column]
    private ?int $max = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(?string $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDuree(): ?DateTime
    {
        return $this->duree;
    }

    public function setDuree(DateTime $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): static
    {
        $this->paiement = $paiement;

        return $this;
    }
}