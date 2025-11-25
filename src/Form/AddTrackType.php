<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Track;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Track Name',
                    'style' => 'background-color: var(--primary); color: white;',
                ]
            ])
            ->add('trackNumber', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control col-md-6 d-inline-block',
                    'placeholder' => 'Track Number',
                    'style' => 'background-color: var(--primary); color: white;',
                ],
                'row_attr' => [
                    'class' => 'col-md-2 d-inline-block me-3 mb-4'
                ],
            ])
            ->add('duration', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control col-md-6 d-inline-block',
                    'placeholder' => 'Duration in seconds',
                    'style' => 'background-color: var(--primary); color: white;',
                ],
                'row_attr' => [
                    'class' => 'col-md-2 d-inline-block me-2'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Track::class,
        ]);
    }
}
