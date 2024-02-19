<?php

namespace App\Entity;

use App\Repository\TypeHebergementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeHebergementRepository::class)]
class TypeHebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: 'type should not be empty')]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description should not be empty')]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Hebergement::class, mappedBy: 'typeHebergement')]
    private Collection $hebergement;

    public function __construct()
    {
        $this->hebergement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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
     * @return Collection<int, Hebergement>
     */
    public function getHebergement(): Collection
    {
        return $this->hebergement;
    }

    public function addHebergement(Hebergement $hebergement): static
    {
        if (!$this->hebergement->contains($hebergement)) {
            $this->hebergement->add($hebergement);
            $hebergement->setTypeHebergement($this);
        }

        return $this;
    }

    public function removeHebergement(Hebergement $hebergement): static
    {
        if ($this->hebergement->removeElement($hebergement)) {
            // set the owning side to null (unless already changed)
            if ($hebergement->getTypeHebergement() === $this) {
                $hebergement->setTypeHebergement(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return (string) $this->getId();
    }

}
