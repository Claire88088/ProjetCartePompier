<?php

namespace App\Form;

use App\Entity\Icone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class IconeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('unicode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez l\'unicode'
                ]
            ])
            ->add('lien', FileType::class, [
                'label' => 'Fichiers de l\'icone',
                'mapped' => false,
                'multiple' => true,
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // Type mime des fichiers qu'il sera possible de joindre
                            'application/vnd.ms-fontobject',
                            'image/svg+xml',
                            'application/font-sfnt',
                            'application/font-woff',
                            'font/woff2'
                        ],
                        'mimeTypesMessage' => 'Seuls les extensions suivantes sont acceptées (.svg, .eot, .ttf, .woff, .woff2)'
                        ])
                ],
                'data_class' => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez les 6 fichiers nécessaires'
                ),
                'row_attr' => ['placeholder' => 'Sélectionnez un fichier'],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Icone::class,
        ]);
    }
}
