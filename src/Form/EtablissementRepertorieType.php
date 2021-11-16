<?php

namespace App\Form;

use App\Entity\EtablissementRepertorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtablissementRepertorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('nom')
            ->add('adresse')
            ->add('coordonneesGPS')
            ->add('description')
            ->add('photo')
            ->add('lien')
            ->add('calque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EtablissementRepertorie::class,
        ]);
    }
}
