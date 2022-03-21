<?php

namespace App\Form;

use App\Entity\Element;
use App\Form\Type\LocalDateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TravauxType extends AbstractType
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
            ->add('dateDeb', DateType::class, [
                'label' => 'Date de début des travaux',
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'Entrez la date de début des travaux'
                ),
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin des travaux',
                'required' => false,
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'Entrez la date de fin des travaux'
                ),
            ])
            ->add('coordonnees', PointType::class, [
                'mapped' => false
            ])
            ->add('couleur', TextType::class, [
                'attr' => [
                    'value' => "#000000",
                    'data-jscolor' => "{}"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Element::class,
        ]);
    }
}
