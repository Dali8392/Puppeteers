<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'adresse should not be empty')]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Price should not be empty')]
    #[Assert\Range(min: 0, minMessage: 'Price should be positive')]
    private ?float $tarif = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description should not be empty')]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'hebergement')]
    private Collection $avis;

    #[ORM\ManyToOne(inversedBy: 'hebergement')]
    #[Assert\NotBlank(message: 'typeHebergement should not be empty')]
    private ?TypeHebergement $typeHebergement = null;

    #[ORM\ManyToOne(inversedBy: 'hebergements')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'etat should not be empty')]
    #[Assert\Choice(
        choices: ['available', 'unavailable'],
        message: 'The state must be either "available" or "unavailable".'
    )]
    private ?string $etat = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'This date should not be empty')]
    #[Assert\GreaterThanOrEqual('today', message: "This date must be greater than or equal to today.")]
    private ?DateTime $dateDisponibilte = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'capacite should not be empty')]
    private ?int $capacite = null;

    #[ORM\OneToMany(targetEntity: Voyage::class, mappedBy: 'hebergement')]
    private Collection $voyages;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Image should not be empty')]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Name should not be empty')]
    private ?string $name = null;
    #[ORM\Column(nullable: true)]
    private ?int $number_likes = null;
    #[ORM\Column(nullable: true)]
    private ?int $number_dislikes = null;

    #[ORM\OneToMany(targetEntity: ReservationHebergement::class, mappedBy: 'hebergement')]
    private Collection $reservationhebergement;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->voyages = new ArrayCollection();
        $this->hebergement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function getnumber_likes(): ?int
    {
        return $this->number_likes;
    }

    public function setnumber_likes(int $numberLikes): static
    {
        $this->number_likes = $numberLikes;

        return $this;
    }

    public function getnumber_dislikes(): ?int
    {
        return $this->number_dislikes;
    }

    public function setnumber_dislikes(int $numberDislikes): static
    {
        $this->number_dislikes = $numberDislikes;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setHebergement($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getHebergement() === $this) {
                $avi->setHebergement(null);
            }
        }

        return $this;
    }

    public function getTypeHebergement(): ?TypeHebergement
    {
        return $this->typeHebergement;
    }

    public function setTypeHebergement(?TypeHebergement $typeHebergement): static
    {
        $this->typeHebergement = $typeHebergement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateDisponibilte(): ?DateTime
    {
        return $this->dateDisponibilte;
    }

    public function setDateDisponibilte(DateTime $dateDisponibilte): static
    {
        $this->dateDisponibilte = $dateDisponibilte;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * @return Collection<int, Voyage>
     */
    public function getVoyages(): Collection
    {
        return $this->voyages;
    }

    public function addVoyage(Voyage $voyage): static
    {
        if (!$this->voyages->contains($voyage)) {
            $this->voyages->add($voyage);
            $voyage->setHebergement($this);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): static
    {
        if ($this->voyages->removeElement($voyage)) {
            // set the owning side to null (unless already changed)
            if ($voyage->getHebergement() === $this) {
                $voyage->setHebergement(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

   
    public function __toString(): string
    {
        return (string) $this->getId();
    }

    /**
     * @return Collection<int, ReservationHebergement>
     */
    public function getReservationHebergement(): Collection
    {
        return $this->reservationhebergement;
    }

    public function addReservationHebergement(ReservationHebergement $reservationhebergement): static
    {
        if (!$this->reservationhebergement->contains($reservationhebergement)) {
            $this->reservationhebergement->add($reservationhebergement);
            $reservationhebergement->setHebergement($this);
        }

        return $this;
    }

    public function removeReservationHebergement(ReservationHebergement $reservationhebergement): static
    {
        if ($this->reservationhebergement->removeElement($reservationhebergement)) {
            // set the owning side to null (unless already changed)
            if ($reservationhebergement->getHebergement() === $this) {
                $reservationhebergement->setHebergement(null);
            }
        }

        return $this;
    }


}