<?php

namespace App\Entity;

use App\Repository\ReservationHebergementRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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
    #[Assert\NotBlank(message: "Date cannot be blank.")]
    #[Assert\Type(type: "\DateTime", message: "Departure Date must be a valid date.")]
    private ?DateTime $date = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Duration cannot be blank.")]
    #[Assert\Type(type: "\DateTime", message: "Arrival Date must be a valid date.")]
    private ?DateTime $duree = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Max cannot be blank.")]
    #[Assert\PositiveOrZero(message: "Max must be a positive number ")]
    private ?int $max = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Paiement $paiement = null;

    #[ORM\ManyToOne(inversedBy: 'reservationhebergement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hebergement $hebergement = null;

    

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

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): static
    {
        $this->hebergement = $hebergement;

        return $this;
    }

     /**
     * @Assert\Callback
     */
    public function validateDateGreaterThanDuree(ExecutionContextInterface $context, $payload)
    {
        // Récupérer la date et la durée
        $date = $this->getDate();
        $duree = $this->getDuree();

        // Vérifier si la date est sup à la durée
        if ($date && $duree && $date > $duree) {
            // Définir un message d'erreur personnalisé
            $message = "The start date must be before the end date.";

            // Ajouter une violation de validation avec le message personnalisé
            $context->buildViolation($message)
                ->atPath('date')  // Choisir le chemin de la propriété en erreur
                ->addViolation();
        }
    }

   // Dans la classe ReservationHebergement

public function calculateAmount(): ?float
{
    // Récupérer la date de réservation et la durée
    $dateReservation = $this->getDate();
    $duree = $this->getDuree();

    // Vérifier si les dates sont valides
    if ($dateReservation && $duree) {
        // Calculer la différence entre la date de réservation et la durée
        $difference = $duree->diff($dateReservation);
        
        // Extraire le nombre de jours de la différence
        $numberOfDays = $difference->days;

        // Vérifier si la réservation a une méthode de paiement associée
        $paiement = $this->getPaiement();
        if ($paiement) {
            // Récupérer le montant du paiement
            $montantPaiement = $paiement->getMontant();
            
            // Vérifier si le montant du paiement est valide
            if ($montantPaiement !== null && $numberOfDays >= 0) {
                // Calculer le montant total en multipliant le nombre de jours par le montant du paiement
                $montantTotal = $numberOfDays * $montantPaiement;
                return $montantTotal;
            }
        }
    }

    return null;
}


}