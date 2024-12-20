<?php

namespace App\Entity;

use App\Repository\LessonMediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonMediaRepository::class)]
class LessonMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lessonMedia')]
    private ?Lessons $lessonId = null;

    #[ORM\ManyToOne(inversedBy: 'lessonMedia')]
    private ?Media $mediaId = null;

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

    public function getMediaId(): ?Media
    {
        return $this->mediaId;
    }

    public function setMediaId(?Media $mediaId): static
    {
        $this->mediaId = $mediaId;

        return $this;
    }
}
