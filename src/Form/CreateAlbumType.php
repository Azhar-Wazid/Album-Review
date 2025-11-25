<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class CreateAlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('albumName', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Album Name',
                    'style' => 'background-color: var(--primary); color: white;',
                ]
            ])
            ->add('releaseDate', ChoiceType::class, [
                'choices' => array_combine(
                    range(date('Y'), 1950),
                    range(date('Y'), 1950)
                ),
                'placeholder' => 'Select release year',
                'required' => true,
                'attr' => [
                    'class' => 'btn btn-secondary btn-lg dropdown-toggle mb-3',
                ]
            ])
            ->add('coverImage', FileType::class, [
                'label' => 'Cover Image',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Artist Name',
                    'style' => 'background-color: var(--primary); color: white;',
                    'accept' => 'image/png, image/jpeg',
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image.',
                    ])
                 ]
            ])
            ->add('artistID', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Artist Name',
                    'style' => 'background-color: var(--primary); color: white;',
                ]
            ])
            ->add('genreID', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'genreName',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
