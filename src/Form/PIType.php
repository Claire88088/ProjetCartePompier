<?php

namespace App\Form;

use App\Entity\Element;
use App\Form\Type\LocalDateTimeType;
use Symfony\Component\Form\AbstractType;
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

class PIType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('icone', ChoiceType::class, [
                'label' => 'Icône',
                'choices' => $options['data']
            ])*/
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'élément'
                ]
            ])
            ->add('texte', TextareaType::class, array(
                'label' => 'Description',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Entrez une description'
                )
            ))
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
                        'mimeTypesMessage' => "La photo n'est pas au bon format", ])
                ],
                'data_class' => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez une photo'
                    ),
                ])
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
