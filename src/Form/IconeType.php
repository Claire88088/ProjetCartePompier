<?php

namespace App\Form;

use App\Entity\Icone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class IconeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez son nom'
                ]
            ])
            ->add('unicode', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez l\'unicode'
                ]
            ])
            ->add('icone', FileType::class, [
                'label' => 'Fichiers de l\'icone',
                'mapped' => false,
                'multiple' => true,
                'required' => true,
                'data_class' => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez les 5 fichiers "Font"'
            ),])
            ->add('icone', FileType::class, [
                'label' => 'Fichiers de l\'icone',
                'mapped' => false,
                'multiple' => true,
                'required' => true,
                'data_class' => null,
                'attr' => array(
                    'placeholder' => 'Sélectionnez les 5 fichiers "Font"'
                ),])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Icone::class,
        ]);
    }
}
