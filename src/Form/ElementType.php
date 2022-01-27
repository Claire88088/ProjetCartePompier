<?php

namespace App\Form;

use App\Entity\Element;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 

class ElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('icone', ChoiceType::class, [
                'label' => 'Icône',
                'choices' => $options['data'],
            ])*/
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
            ->add('lien', FileType::class, [
                'label' => 'Lien (PDF)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // Type mime des fichiers qu'il sera possible de joindre
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Seuls les fichiers PDF sont acceptés', ])
                ],
                'data_class'   => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez un PDF'
                    ),
                ])
            ->add('dateDeb', DateTimeType::class, [
                'label' => 'Date de début',
                'required' => false,
                'widget'=> 'single_text',
                'attr' => array(
                    'placeholder' => 'Entrez la date de début'
                ),
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date de fin',
                'required' => false,
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'Entrez la date de fin'
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
