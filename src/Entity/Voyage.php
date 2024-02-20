<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    private ?string $depart = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    private ?string $destination = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\GreaterThanOrEqual('today' , message:"This date must be > today.")]
    private ?\DateTimeInterface $DateDep = null;
    
    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\GreaterThanOrEqual(propertyPath: 'date_dep' , message:"Arrival Date must be > Departure Date.")]
    #[Assert\GreaterThanOrEqual('today' , message:"This date must be > today.")]
    private ?\DateTimeInterface $DateArr = null;
    
    #[ORM\Column(type: 'time')]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    // #[Assert\LessThan(propertyPath: 'heure_arr' , message:"Departure Time must be < Arrival Time.")]
    private ?\DateTimeInterface $HeureDep = null;
    
    #[ORM\Column(type: 'time')]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    // #[Assert\GreaterThan(propertyPath: 'heure_dep' , message:"Departure Time must be < Arrival Time.")]
    private ?\DateTimeInterface $HeureArr = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\Positive(message:"This field must be > 0.")]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    #[Assert\Positive(message:"This field must be > 0.")]

    private ?int $NombrePlaceDispo = null;
    
    #[ORM\ManyToOne (targetEntity:MoyenTransport::class)]
    #[ORM\JoinColumn(name:'moyen_transport_id', referencedColumnName:'id')]
    #[Assert\NotNull(message:"This field is mandatory.")]
    private ?MoyenTransport $moyenTransport = null;

    #[ORM\ManyToOne(inversedBy: 'voyages')]
    // #[Assert\NotNull(message:"This field is mandatory.")]
    private ?Hebergement $hebergement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"This field is mandatory.")]
    private ?string $description = null;


/**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        $dateDep= $this ->getDateDep();
        $heureDep=$this->getHeureDep();
        $dateArr= $this ->getDateArr();
        $heureArr=$this->getHeureArr();
        $datetimeDep = new \DateTime($dateDep->format('Y-m-d') . ' ' . $heureDep->format('H:i:s'));
        $datetimeArr = new \DateTime($dateArr->format('Y-m-d') . ' ' . $heureArr->format('H:i:s'));       
        
        if ($datetimeArr < $datetimeDep) {
        $context->buildViolation('Departure and arrival dates and times are not compatible')->atPath('HeureDep')->addViolation();
    }

    }
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
        return $this->DateDep;
    }

    public function setDateDep(DateTime $date_dep): static
    {
        $this->DateDep = $date_dep;

        return $this;
    }

    public function getDateArr(): ?DateTime
    {
        return $this->DateArr;
    }

    public function setDateArr(DateTime $date_arr): static
    {
        $this->DateArr = $date_arr;

        return $this;
    }

    public function getHeureDep(): ?DateTime
    {
        return $this->HeureDep;
    }

    public function setHeureDep(DateTime $heure_dep): static
    {
        $this->HeureDep = $heure_dep;

        return $this;
    }

    public function getHeureArr(): ?DateTime
    {
        return $this->HeureArr;
    }

    public function setHeureArr(DateTime $heure_arr): static
    {
        $this->HeureArr = $heure_arr;

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
        return $this->NombrePlaceDispo;
    }

    public function setNombrePlaceDispo(int $nombre_place_dispo): static
    {
        $this->NombrePlaceDispo = $nombre_place_dispo;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}