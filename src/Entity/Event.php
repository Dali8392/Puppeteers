<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $name = null;

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

    #[ORM\Column]
    private ?int $max_participants = null;

    #[ORM\Column]
    private ?float $budget_allocated = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

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

        return $this;
    }

    public function getDateFin(): ?DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(DateTime $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
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

    public function getDuree(): ?DateTime
    {
        return $this->duree;
    }

    public function setDuree(DateTime $duree): static
    {
        $this->duree = $duree;

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

    public function setStatus(string $status): static
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