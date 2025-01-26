<?php

namespace App\Form;

use App\Entity\Chapters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChaptersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $maxIndex = $options['max_index']; // Retrieve the 'max_index' option
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => ['placeholder' => 'Titre de la leçon'],
            ])
            ->add('description', TextType::class)
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'attr'  => [
                    'placeholder' => 'Position de la leçon',
                    'max' => $maxIndex + 1,
                    'value' => $maxIndex + 1,
                    'min' => 1,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapters::class,
            'max_index'  => 0, // Provide a default value for 'max_index'
        ]);

        $resolver->setAllowedTypes('max_index', 'int'); // Ensure 'max_index' is an integer
    }
}
