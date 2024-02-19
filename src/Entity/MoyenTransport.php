<?php

namespace App\Entity;

use App\Repository\MoyenTransportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoyenTransportRepository::class)]
class MoyenTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie_moyen = null;

    #[ORM\Column(length: 255)]
    private ?string $type_moyen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieMoyen(): ?string
    {
        return $this->categorie_moyen;
    }

    public function setCategorieMoyen(string $categorie_moyen): static
    {
        $this->categorie_moyen = $categorie_moyen;

        return $this;
    }

    public function getTypeMoyen(): ?string
    {
        return $this->type_moyen;
    }

    public function setTypeMoyen(string $type_moyen): static
    {
        $this->type_moyen = $type_moyen;

        return $this;
    }
}
