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
    private ?Lessons $lessonId = null;

    #[ORM\ManyToOne(inversedBy: 'lessonProgress')]
    private ?User $studentId = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLessonId(): ?Lessons
    {
        return $this->lessonId;
    }

    public function setLessonId(?Lessons $lessonId): static
    {
        $this->lessonId = $lessonId;

        return $this;
    }

    public function getStudentId(): ?User
    {
        return $this->studentId;
    }

    public function setStudentId(?User $studentId): static
    {
        $this->studentId = $studentId;

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
