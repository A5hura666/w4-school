<?php

namespace App\Form\DataTransformer;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\DataTransformerInterface;

class FileToMediaTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $em;
    private string $uploadDirectory;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setUploadDirectory(string $uploadDirectory): void
    {
        $this->uploadDirectory = $uploadDirectory;
    }

    public function transform($value): ?UploadedFile
    {
        // No transformation needed when displaying the form
        return null;
    }

    public function reverseTransform($value): ?Media
    {
        if (!$value instanceof UploadedFile) {
            return null;
        }

        // Correction du nom de fichier
        $fileName = uniqid() . '.' . $value->guessExtension();

        // Déplacement du fichier dans le répertoire défini
        $value->move($this->uploadDirectory, $fileName);

        // Création de l'entité Media
        $media = new Media();
        $media->setFileName($fileName);
        $media->setFilePath($fileName);
        $media->setMediaType($value->getClientMimeType());
        $media->setUploadedAt(new \DateTimeImmutable());
        $media->setRelatedType(Media::TYPE_COURSES);

        // Persistance de l'entité
        $this->em->persist($media);

        return $media;
    }
}
