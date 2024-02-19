<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"nom de la ville ne doit pas etre vide")] 
    #[Assert\Length(max:20,maxMessage:"Le nom de la ville ne doit pas contenir plus que 20 caracteres")] 
    private ?string $ville = null;


    #[Assert\NotBlank(message:"prix ne doit pas etre vide")] 
    #[Assert\GreaterThanOrEqual(value:0, message:"Prix doit être au moins 0 €")]
    #[Assert\LessThanOrEqual(value:100, message:"Prix ne peut pas dépasser 100 €")]
    #[ORM\Column(length: 255)]
    private ?string $prix = null;


    #[Assert\NotBlank(message:"details ne doit pas etre vide")] 

    #[ORM\Column(length: 255)]
    private ?string $details = null;
    #[Assert\NotBlank(message:"heure ne doit pas etre vide")] 

    #[ORM\Column(length: 25)]
    private ?string $heure = null;

    #[ORM\Column]
    private ?int $etat = 0;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'activites')]
    private Collection $participants;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    private ?Guide $guide = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getGuide(): ?Guide
    {
        return $this->guide;
    }

    public function setGuide(?Guide $guide): static
    {
        $this->guide = $guide;

        return $this;
    }
}