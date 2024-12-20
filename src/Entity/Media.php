<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $FilePath = null;

    #[ORM\Column(length: 255)]
    private ?string $mediaType = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    /**
     * @var Collection<int, LessonMedia>
     */
    #[ORM\OneToMany(targetEntity: LessonMedia::class, mappedBy: 'mediaId')]
    private Collection $lessonMedia;

    public function __construct()
    {
        $this->lessonMedia = new ArrayCollection();
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
        return $this->FilePath;
    }

    public function setFilePath(string $FilePath): static
    {
        $this->FilePath = $FilePath;

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

    /**
     * @return Collection<int, LessonMedia>
     */
    public function getLessonMedia(): Collection
    {
        return $this->lessonMedia;
    }

    public function addLessonMedium(LessonMedia $lessonMedium): static
    {
        if (!$this->lessonMedia->contains($lessonMedium)) {
            $this->lessonMedia->add($lessonMedium);
            $lessonMedium->setMediaId($this);
        }

        return $this;
    }

    public function removeLessonMedium(LessonMedia $lessonMedium): static
    {
        if ($this->lessonMedia->removeElement($lessonMedium)) {
            // set the owning side to null (unless already changed)
            if ($lessonMedium->getMediaId() === $this) {
                $lessonMedium->setMediaId(null);
            }
        }

        return $this;
    }
}
