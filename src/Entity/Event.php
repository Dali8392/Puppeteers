<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Event Name should not be blank")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z]+$/",
        message: "Event Name should contain only letters"
    )]
    #[Assert\Length(max: 10, maxMessage: "Event Name should not be longer than 10 characters")]
    #[ORM\Column(length: 10)]
    private ?string $name = null;

    #[Assert\NotBlank(message: "Event Type should not be blank")]
    #[Assert\Length(max: 10, maxMessage: "Event Type should not be longer than 10 characters")]
    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\Column]
    private ?DateTime $date_debut = null;

    #[ORM\Column]
    private ?DateTime $date_fin = null;

    #[ORM\Column(length: 255)]
    private ?string $event_location = null;

    #[ORM\Column]
    private ?DateTime $duree = null;

    #[Assert\NotBlank(message: "Max Event Participants should not be blank")]
    #[Assert\PositiveOrZero(message: "Max Event Participants should be a positive number or zero")]
    #[ORM\Column(type: 'integer')]
    private ?int $max_participants = null;

    #[Assert\NotBlank(message: "Event Budget should not be blank")]
    #[Assert\PositiveOrZero(message: "Event Budget should be a positive number or zero")]
    #[Assert\Regex(
        pattern: '/^\d*\.?\d*$/',
        message: "Event Budget should contain only numbers"
    )]
    #[ORM\Column(type: 'float')]
    private ?float $budget_allocated = null;

    #[ORM\Column(length: 255)]
    private $status = 'Permission';

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'event')]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $participants;

    #[ORM\Column(length: 10)]
    private ?string $userCreator = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateDebut(): ?DateTime
    {
        return $this->date_debut;
    }

    public function setDateDebut(DateTime $date_debut): static
    {
        $this->date_debut = $date_debut;
        $this->setDuree();

        return $this;
    }

    public function getDateFin(): ?DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;
        $this->setDuree();

        return $this;
    }
  /**
     * @Assert\IsTrue(message="End date must be greater than start date")
     */
    public function isEndDateGreaterThanStartDate(): bool
    {
        return $this->date_fin > $this->date_debut;
    }
    public function getEventLocation(): ?string
    {
        return $this->event_location;
    }

    public function setEventLocation(string $event_location): static
    {
        $this->event_location = $event_location;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId(); 
    }
    public function getDuree(): ?DateTime
    {
        return $this->duree;
    }

    public function setDuree(): static
    {
        // Check if both start and end dates are set
        if ($this->date_debut !== null && $this->date_fin !== null) {
            // Calculate the difference in seconds between the two dates
            $difference = $this->date_fin->getTimestamp() - $this->date_debut->getTimestamp();

            // Calculate years, months, days, hours, minutes, seconds
            $years = floor($difference / (365 * 24 * 60 * 60));
            $difference %= (365 * 24 * 60 * 60);

            $months = floor($difference / (30 * 24 * 60 * 60));
            $difference %= (30 * 24 * 60 * 60);

            $days = floor($difference / (24 * 60 * 60));
            $difference %= (24 * 60 * 60);

            $hours = floor($difference / (60 * 60));
            $difference %= (60 * 60);

            $minutes = floor($difference / 60);
            $seconds = $difference % 60;

            // Create a new DateTime object with the calculated values
            $this->duree = new DateTime();
            $this->duree->setDate($years, $months, $days);
            $this->duree->setTime($hours, $minutes, $seconds);
        }

        return $this;
    }
       


    public function getMaxParticipants(): ?int
    {
        return $this->max_participants;
    }

    public function setMaxParticipants(int $max_participants): static
    {
        $this->max_participants = $max_participants;

        return $this;
    }

    public function getBudgetAllocated(): ?float
    {
        return $this->budget_allocated;
    }

    public function setBudgetAllocated(float $budget_allocated): static
    {
        $this->budget_allocated = $budget_allocated;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

/**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

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

    public function getUserCreator(): ?string
    {
        return $this->userCreator;
    }

    public function setUserCreator(string $userCreator): static
{
    $this->userCreator = $userCreator;

    return $this;
}
}