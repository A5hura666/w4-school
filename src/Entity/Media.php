<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    public const TYPE_VIDEO = 'video';
    public const TYPE_IMAGES = 'image';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_DOCUMENTS = 'document';

    public const RELATED_COURSES = 'courses';
    public const RELATED_CHAPTERS = 'chapters';
    public const RELATED_LESSONS = 'lessons';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\Column(length: 255)]
    private ?string $mediaType = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $relatedId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relatedType = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): static
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getRelatedId(): ?int
    {
        return $this->relatedId;
    }

    public function setRelatedId(?int $relatedId): static
    {
        $this->relatedId = $relatedId;

        return $this;
    }

    public function getRelatedType(): ?string
    {
        return $this->relatedType;
    }

    public function setRelatedType(?string $relatedType): static
    {
        $this->relatedType = $relatedType;

        return $this;
    }

    public static function getAvailableRelated(): array
    {
        return [
            self::RELATED_LESSONS,
            self::RELATED_CHAPTERS,
            self::RELATED_COURSES,
        ];
    }
}