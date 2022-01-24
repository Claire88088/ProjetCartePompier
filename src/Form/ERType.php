<?php

namespace App\Form;

use App\Entity\Element;
use App\Form\Type\LocalDateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ERType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeElement', ChoiceType::class, [
                'label' => "Type d'élément",
                'choices'  => $options['data']['typeEltChoices'],
            ])
            ->add('icone', ChoiceType::class, [
                'label' => 'Icône',
                'choices' => $options['data']['iconeChoices']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo (jpeg ou png)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // Type mime des fichiers qu'il sera possible de joindre
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => "La photos n'est pas au bon format", ])
                ],
                'data_class' => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez une photo'
                    ),
                ])
            ->add('lien', FileType::class, [
                'label' => 'Lien (PDF)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // Type mime des fichiers qu'il sera possible de joindre
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Seul les fichiers PDF sont acceptés', ])
                ],
                'data_class' => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez un PDF'
                ),
                'row_attr' => ['placeholder' => 'Sélectionnez un fichier'],])
            ->add('texte', TextareaType::class, array(
            'label' => 'Description',
            'attr' => array(
                'placeholder' => 'Entrez une description'
                )
            ))
            ->add('coordonnees', PointType::class, [
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Element::class,
        ]);
    }
}
