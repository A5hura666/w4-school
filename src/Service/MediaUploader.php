<?php

namespace App\Service;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

class MediaUploader
{
    private string $uploadsDirectory;
    private EntityManagerInterface $entityManager;

    public function __construct(string $uploadsDirectory, EntityManagerInterface $entityManager)
    {
        $this->uploadsDirectory = $uploadsDirectory;
        $this->entityManager = $entityManager;
    }

    public function upload(UploadedFile $file, int $relatedId, string $mediaType, string $relatedType): Media
    {
        $media = new Media();
        $extension = $file->guessExtension();
        $fileName = uniqid() . '.' . $extension;
        $file->move($this->uploadsDirectory, $fileName);

        $media->setFileName($fileName);
        $media->setMediaType($mediaType);
        $media->setUploadedAt(new \DateTimeImmutable());
        $media->setFilePath($this->uploadsDirectory . '/' . $fileName);
        $media->setRelatedId($relatedId);
        $media->setRelatedType($relatedType);

        return $media;
    }
}