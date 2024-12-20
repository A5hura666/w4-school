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
    private ?Courses $courseId = null;

    #[ORM\ManyToOne(inversedBy: 'courseTags')]
    private ?Tags $tagId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourseId(): ?Courses
    {
        return $this->courseId;
    }

    public function setCourseId(?Courses $courseId): static
    {
        $this->courseId = $courseId;

        return $this;
    }

    public function getTagId(): ?Tags
    {
        return $this->tagId;
    }

    public function setTagId(?Tags $tagId): static
    {
        $this->tagId = $tagId;

        return $this;
    }
}
