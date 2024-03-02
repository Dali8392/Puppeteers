<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    #[Assert\NotBlank(message: "The amount cannot be empty.")]
    #[Assert\Type(type: ['integer', 'float'], message: "The amount must be an integer or float.")]
    #[Assert\Positive(message: "The amount must be positive.")]
    private ?float $montant = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "The date cannot be empty.")]
    #[Assert\Type(type: 'DateTime', message: "The date must be in DateTime format.")]
    private ?DateTime $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The payment method cannot be empty.")]
    private ?string $methode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

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

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): static
    {
        $this->methode = $methode;

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->id; 
    }

     
}