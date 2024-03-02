<?php

namespace App\Entity;

use App\Repository\ReservationVoyageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationVoyageRepository::class)]
class ReservationVoyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Date cannot be blank.")]
    #[Assert\Type(type: "\DateTime", message: "Departure Date must be a valid date.")]
    private ?DateTime $date = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Max cannot be blank.")]
    #[Assert\Positive(message: "Max must be a positive number .")]
    private ?int $max = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "User ID cannot be blank.")]
    #[Assert\Length(max: 10, maxMessage: "User ID cannot be longer than 6 characters.")]
    private ?string $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'reservationvoyage')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Voyage $voyage = null;
    public function getId(): ?int
    {
        return $this->id;
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

    public function getVoyage(): ?Voyage
    {
        return $this->voyage;
    }

    public function setVoyage(?Voyage $voyage): static
    {
        $this->voyage = $voyage;

        return $this;
    }

     

   
}