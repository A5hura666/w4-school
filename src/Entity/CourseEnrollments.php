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
    private ?Courses $courses = null;

    #[ORM\ManyToOne(inversedBy: 'courseEnrollments')]
    private ?User $student = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $anrolledAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourses(): ?Courses
    {
        return $this->courses;
    }

    public function setCourses(?Courses $courses): static
    {
        $this->courses = $courses;

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
