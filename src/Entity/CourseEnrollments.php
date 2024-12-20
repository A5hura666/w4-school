<?php

namespace App\Entity;

use App\Repository\CourseEnrollmentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseEnrollmentsRepository::class)]
class CourseEnrollments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courseEnrollments')]
    private ?Courses $coursesId = null;

    #[ORM\ManyToOne(inversedBy: 'courseEnrollments')]
    private ?User $studentId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $anrolledAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoursesId(): ?Courses
    {
        return $this->coursesId;
    }

    public function setCoursesId(?Courses $coursesId): static
    {
        $this->coursesId = $coursesId;

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

    public function getAnrolledAt(): ?\DateTimeImmutable
    {
        return $this->anrolledAt;
    }

    public function setAnrolledAt(\DateTimeImmutable $anrolledAt): static
    {
        $this->anrolledAt = $anrolledAt;

        return $this;
    }
}
