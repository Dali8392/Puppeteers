<?php

namespace App\Entity;

use App\Repository\MoyenTransportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: MoyenTransportRepository::class)]
class MoyenTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\Choice(choices: ["Terrestre", "Aérien"], message: "Invalid type. (Terrestre / Aérien)")]
    private ?string $categorieMoyen = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\Choice(choices: ["Avion", "Bus"], message: "Invalid type. (Avion / Bus)")]
    private ?string $typeMoyen = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    private ?string $idModele = null;


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getTypeMoyen() === "Avion") {
            if (!preg_match('/^[A-Za-z]{3,4}\d{2,4}$/', $this->getIdModele())) {
                $context->buildViolation('This is not a valid model. (Plane)')->atPath('idModele')->addViolation();
            }
        }
        elseif ($this->getTypeMoyen() === "Bus") {
            if (!preg_match('/^\d{2,3}[A-Za-z]{3}\d{2,4}$/', $this->getIdModele())) {
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
}
