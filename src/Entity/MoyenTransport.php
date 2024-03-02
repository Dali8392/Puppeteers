<?php

namespace App\Entity;

use App\Repository\MoyenTransportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntityConstraint;

#[ORM\Entity(repositoryClass: MoyenTransportRepository::class)]
#[UniqueEntityConstraint(fields:"idModele", message :"This model already exists.")]
class MoyenTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\Choice(choices: ["Land", "Air"], message: "Invalid type. (Land / Air)")]
    private ?string $categorieMoyen = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\Choice(choices: ["Plane", "Bus"], message: "Invalid type. (Plane / Bus)")]
    private ?string $typeMoyen = null;

    #[ORM\Column(length: 255,unique: true)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    private ?string $idModele = null;


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (($this->getTypeMoyen() === "Plane" && $this->getCategorieMoyen() === "Land") ||
        ($this->getTypeMoyen() === "Bus" && $this->getCategorieMoyen() === "Air")) {
        $context->buildViolation('Type and category are not compatible.')->atPath('categorieMoyen')->addViolation();
    }

        if ($this->getTypeMoyen() === "Plane") {
            if (!preg_match('/^[A-Za-z]{3,4}\d{2,4}$/', $this->getIdModele()) && $this->getIdModele() !== null) {
                $context->buildViolation('This is not a valid model. (Plane)')->atPath('idModele')->addViolation();
            }
        }
        elseif ($this->getTypeMoyen() === "Bus") {
            if (!preg_match('/^\d{2,3}[A-Za-z]{3}\d{2,4}$/', $this->getIdModele()) && $this->getIdModele() !== null) {
                $context->buildViolation('This is not a valid model.(Bus)')->atPath('idModele')->addViolation();
            }
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieMoyen(): ?string
    {
        return $this->categorieMoyen;
    }

    public function setCategorieMoyen(string $categorie_moyen): static
    {
        $this->categorieMoyen = $categorie_moyen;

        return $this;
    }

    public function getTypeMoyen(): ?string
    {
        return $this->typeMoyen;
    }

    public function setTypeMoyen(string $type_moyen): static
    {
        $this->typeMoyen = $type_moyen;

        return $this;
    }

    public function getIdModele(): ?string
    {
        return $this->idModele;
    }

    public function setIdModele(?string $idModele): static
    {
        $this->idModele = $idModele;

        return $this;
    }

    public function __toString()
    {
        // Return a string representation of your entity
        return "ID :".$this->id."; Type :".$this->getTypeMoyen()."; Model :".$this->getIdModele();
    }
}
