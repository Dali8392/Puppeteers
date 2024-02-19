<?php

namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 


#[ORM\Entity(repositoryClass: GuideRepository::class)]

class Guide 
{
    #[ORM\Id]
    #[ORM\Column(length: 10)]
    private ?string $id = null;

    #[Assert\NotBlank(message:"prenom ne doit pas etre vide")] 

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[Assert\NotBlank(message:"nom ne doit pas etre vide")] 
    #[ORM\Column(length: 20)]
    private ?string $lastName = null;

    #[Assert\NotBlank(message:"email ne doit pas etre vide")] 
    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[Assert\NotBlank(message:"cin ne doit pas etre vide")] 
    #[ORM\Column(length: 8)]
    private ?string $cin = null;

    #[Assert\NotBlank(message:"role ne doit pas etre vide")] 
    #[ORM\Column(length: 10)]
    private ?string $role = "guide";

    #[Assert\NotBlank(message:"langue ne doit pas etre vide")] 
    #[ORM\Column(length: 20)]
    private ?string $langue = "vide";


    #[Assert\NotBlank(message:"nom de la ville ne doit pas etre vide")] 

    #[ORM\Column(length: 20)]
    private ?string $ville = "vide";

    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'guide')]
    private Collection $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }
    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function setCord( User $user) {
        $this->id=$user->getId();
        $this->name=$user->getName();
        $this->lastName=$user->getLastName();
        $this->email=$user->getEmail();
        $this->cin=$user->getCin();
    }
    public function getId(): ?string
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
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

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->setGuide($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getGuide() === $this) {
                $activite->setGuide(null);
            }
        }

        return $this;
    }
}