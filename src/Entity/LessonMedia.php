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
    private ?Lessons $lesson = null;

    #[ORM\ManyToOne(inversedBy: 'lessonMedia')]
    private ?Media $media = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }

    public function setLesson(?Lessons $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMediaId(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }
}
