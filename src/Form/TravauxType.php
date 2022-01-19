<?php

namespace App\Form;

use App\Entity\Element;
use App\Form\Type\LocalDateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TravauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDeb', DateTimeType::class, [
                'widget' => 'single_text',
                //'html5' => false,
                //'format' => 'dd-MM-yyyy HH:mm'
            ])
            ->add('dateFin', DateTimeType::class, [
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
