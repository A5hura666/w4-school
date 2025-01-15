<?php

namespace App\Entity;

use App\Repository\LessonsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
class Lessons implements Sortable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chapters $chapter = null;

    /**
     * @var Collection<int, Exercises>
     */
    #[ORM\OneToMany(targetEntity: Exercises::class, mappedBy: 'lesson', cascade: ['remove'])]
    private Collection $exercises;

    /**
     * @var Collection<int, LessonProgress>
     */
    #[ORM\OneToMany(targetEntity: LessonProgress::class, mappedBy: 'lesson', cascade: ['remove'])]
    private Collection $lessonProgress;

    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->lessonProgress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getChapterId(): ?Chapters
    {
        return $this->chapter;
    }

    public function setChapterId(?Chapters $chapter): static
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Collection<int, Exercises>
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercises $exercise): static
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises->add($exercise);
            $exercise->setLessonId($this);
        }

        return $this;
    }

    public function removeExercise(Exercises $exercise): static
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getLessonId() === $this) {
                $exercise->setLessonId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LessonProgress>
     */
    public function getLessonProgress(): Collection
    {
        return $this->lessonProgress;
    }

    public function addLessonProgress(LessonProgress $lessonProgress): static
    {
        if (!$this->lessonProgress->contains($lessonProgress)) {
            $this->lessonProgress->add($lessonProgress);
            $lessonProgress->setLessonId($this);
        }

        return $this;
    }

    public function removeLessonProgress(LessonProgress $lessonProgress): static
    {
        if ($this->lessonProgress->removeElement($lessonProgress)) {
            // set the owning side to null (unless already changed)
            if ($lessonProgress->getLesson() === $this) {
                $lessonProgress->setLessonId(null);
            }
        }

        return $this;
    }
}
