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

class ElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('icone', ChoiceType::class, [
                'label' => 'Icône',
                'choices' => $options['data']
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Description'
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
                'help' => 'Sélectionnez une photo',
                'row_attr' => ['placeholder' => 'Sélectionnez un fichier'],])
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
                'data_class'   => null,
                'help' => 'Sélectionnez un PDF',
                'row_attr' => ['placeholder' => 'Sélectionnez un fichier'],])
            ->add('dateDeb', DateTimeType::class, [
                'label' => 'Date de début',
                'widget'=> 'single_text',
                //'html5' => false,
                //'format' => 'dd-MM-yyyy HH:mm'
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
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
