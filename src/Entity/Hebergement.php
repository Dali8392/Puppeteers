<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HebergementRepository::class)]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?float $tarif = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'hebergement')]
    private Collection $avis;

    #[ORM\ManyToOne(inversedBy: 'hebergement')]
    private ?TypeHebergement $typeHebergement = null;

    #[ORM\ManyToOne(inversedBy: 'hebergements')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column]
    private ?DateTime $dateDisponibilte = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\OneToMany(targetEntity: Voyage::class, mappedBy: 'hebergement')]
    private Collection $voyages;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->voyages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}