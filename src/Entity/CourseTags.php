<?php

namespace App\Entity;

use App\Repository\CourseTagsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseTagsRepository::class)]
class CourseTags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courseTags')]
    private ?Courses $course = null;

    #[ORM\ManyToOne(inversedBy: 'courseTags')]
    private ?Tags $tag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourseId(): ?Courses
    {
        return $this->course;
    }

    public function setCourseId(?Courses $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getTagId(): ?Tags
    {
        return $this->tag;
    }

    public function setTagId(?Tags $tag): static
    {
        $this->tag = $tag;

        return $this;
    }
}
