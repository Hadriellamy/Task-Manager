<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TaskPriority;  // Import de TaskPriority
use App\Entity\TaskStatus;   // Import de TaskStatus

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateLimite = null;

    #[ORM\ManyToOne(inversedBy: 'tasksCreated')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tasksSharedWithMe')]
    private Collection $sharedWith;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'task', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column(type: 'string', enumType: TaskPriority::class)]  // Ajout de la colonne pour la priorité
    private TaskPriority $priorite;

    #[ORM\Column(type: 'string', enumType: TaskStatus::class)]  // Ajout de la colonne pour le statut
    private TaskStatus $statut;

    public function __construct()
    {
        $this->sharedWith = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getDateLimite(): ?\DateTime
    {
        return $this->dateLimite;
    }

    public function setDateLimite(?\DateTime $dateLimite): static
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSharedWith(): Collection
    {
        return $this->sharedWith;
    }

    public function addSharedWith(User $sharedWith): static
    {
        if (!$this->sharedWith->contains($sharedWith)) {
            $this->sharedWith->add($sharedWith);
            $sharedWith->addTasksSharedWithMe($this);
        }

        return $this;
    }

    public function removeSharedWith(User $sharedWith): static
    {
        if ($this->sharedWith->removeElement($sharedWith)) {
            $sharedWith->removeTasksSharedWithMe($this);
        }

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
            $comment->setTask($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTask() === $this) {
                $comment->setTask(null);
            }
        }

        return $this;
    }

    // Getter et setter pour la priorité
    public function getPriorite(): TaskPriority
    {
        return $this->priorite;
    }

    public function setPriorite(TaskPriority $priorite): static
    {
        $this->priorite = $priorite;
        return $this;
    }

    // Getter et setter pour le statut
    public function getStatut(): TaskStatus
    {
        return $this->statut;
    }

    public function setStatut(TaskStatus $statut): static
    {
        $this->statut = $statut;
        return $this;
    }
}
