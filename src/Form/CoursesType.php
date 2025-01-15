<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class CoursesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du cours',
                'attr' => [
                    'class' => 'form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du cours',
                'attr' => [
                    'class' => 'form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                ],
            ]);

        if ($options['has_illustration'] && $options['media_type']) {
            $builder
                ->add('illustration', FileType::class, [
                    'label'       => 'Modifier l\'ilustration',
                    'mapped'      => false,
                    'required'    => false,
                    'constraints' => match ($options['media_type'])
                    {
                        Media::TYPE_IMAGES => [
                            new Image([
                                'maxSize'          => '5M',
                                'mimeTypes'        => [
                                    'image/jpeg',
                                    'image/png',
                                    'image/jpg',
                                ],
                                'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG ou JPEG).',
                            ]),
                        ],
                        Media::TYPE_DOCUMENTS => [
                            new File([
                                'maxSize'          => '10M',
                                'mimeTypes'        => [
                                    'application/pdf',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
                                    'application/msword', // DOC
                                    'application/vnd.ms-excel', // XLS
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX
                                    'text/plain', // TXT
                                ],
                                'mimeTypesMessage' => 'Veuillez uploader un fichier valide (PDF, DOC, DOCX, XLS, XLSX, TXT).',
                            ]),
                        ],
                        default => [],
                    },
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
            'has_illustration' => false,
            'media_type' => null,
        ]);
    }
}
