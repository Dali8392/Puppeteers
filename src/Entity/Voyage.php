<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $depart = null;

    #[ORM\Column(length: 255)]
    private ?string $destination = null;

    #[ORM\Column]
    private ?DateTime $date_dep = null;

    #[ORM\Column(length: 255)]
    private ?DateTime $date_arr = null;

    #[ORM\Column(length: 255)]
    private ?DateTime $heure_dep = null;

    #[ORM\Column(length: 255)]
    private ?DateTime $heure_arr = null;

    #[ORM\Column(length: 255)]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?int $nombre_place_dispo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?MoyenTransport $moyenTransport = null;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    private ?Hebergement $hebergement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDateDep(): ?DateTime
    {
        return $this->date_dep;
    }

    public function setDateDep(DateTime $date_dep): static
    {
        $this->date_dep = $date_dep;

        return $this;
    }

    public function getDateArr(): ?DateTime
    {
        return $this->date_arr;
    }

    public function setDateArr(DateTime $date_arr): static
    {
        $this->date_arr = $date_arr;

        return $this;
    }

    public function getHeureDep(): ?DateTime
    {
        return $this->heure_dep;
    }

    public function setHeureDep(DateTime $heure_dep): static
    {
        $this->heure_dep = $heure_dep;

        return $this;
    }

    public function getHeureArr(): ?DateTime
    {
        return $this->heure_arr;
    }

    public function setHeureArr(DateTime $heure_arr): static
    {
        $this->heure_arr = $heure_arr;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNombrePlaceDispo(): ?int
    {
        return $this->nombre_place_dispo;
    }

    public function setNombrePlaceDispo(int $nombre_place_dispo): static
    {
        $this->nombre_place_dispo = $nombre_place_dispo;

        return $this;
    }

    public function getMoyenTransport(): ?MoyenTransport
    {
        return $this->moyenTransport;
    }

    public function setMoyenTransport(?MoyenTransport $moyenTransport): static
    {
        $this->moyenTransport = $moyenTransport;

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
}