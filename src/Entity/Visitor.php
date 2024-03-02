<?php

namespace App\Entity;

use App\Repository\VisitorRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitorRepository::class)]
class Visitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $ipAddress = null;

    #[ORM\Column]
    private ?DateTime $visitDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getDateInscri(): ?DateTime
    {
        return $this->visitDate;
    }

    public function setDateInscri(DateTime $visitDate): static
    {
        $this->visitDate = $visitDate;

        return $this;
    }
}