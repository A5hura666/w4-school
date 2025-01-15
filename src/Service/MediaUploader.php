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
        $uploadsDirectory = $this->uploadsDirectory . '/' . $mediaType;
        $file->move($uploadsDirectory, $fileName);

        $media->setFileName($fileName);
        $media->setMediaType($mediaType);
        $media->setUploadedAt(new \DateTimeImmutable());
        $media->setFilePath('uploads/'. $mediaType . '/' . $fileName);
        $media->setRelatedId($relatedId);
        $media->setRelatedType($relatedType);

        return $media;
    }

    public function remove(Media $media): void
    {
        $filePath = $this->uploadsDirectory . '/' . $media->getMediaType() . '/' . $media->getFileName();

        if (!file_exists($filePath)) {
            return;
        }

        try {
            unlink($filePath);
            $this->entityManager->remove($media);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \RuntimeException('Une erreur s’est produite lors de la suppression du fichier ou de l’entité.');
        }
    }
}