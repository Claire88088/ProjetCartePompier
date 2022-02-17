<?php

namespace App\Form;

use App\Entity\Element;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AutorouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('icone', ChoiceType::class, [
                'label' => 'Icône',
                'choices' => $options['data']['iconeChoices']
            ])
            ->add('typeElement', ChoiceType::class, [
                'label' => "Type d'élément",
                'choices'  => $options['data']['typeEltChoices'],
            ])*/
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'élément'
                ]
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
