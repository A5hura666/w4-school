<?php

namespace App\Entity;

use App\Repository\LessonProgressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonProgressRepository::class)]
class LessonProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lessonProgress')]
    private ?Lessons $lesson = null;

    #[ORM\ManyToOne(inversedBy: 'lessonProgress')]
    private ?User $student = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }

    public function setLessonId(?Lessons $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): static
    {
        $this->student = $student;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
