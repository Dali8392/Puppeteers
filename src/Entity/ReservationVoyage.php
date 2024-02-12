<?php

namespace App\Entity;

use App\Repository\ReservationVoyageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationVoyageRepository::class)]
class ReservationVoyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private ?int $max = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    #[ORM\Column(length: 10)]
    private ?string $idUser = null;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(string $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }
}