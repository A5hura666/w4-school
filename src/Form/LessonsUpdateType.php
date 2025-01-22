<?php

namespace App\Form;

use App\Entity\Lessons;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonsUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lesson = $options['data'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => ['placeholder' => 'Titre de la leçon'],
            ])
            ->add('content', CKEditorType::class)
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'attr'  => [
                    'placeholder' => 'Position de la leçon',
                    'max' => $lesson->getPosition() + 1,
                    'value' => $lesson->getPosition(),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lessons::class,
        ]);
    }
}
