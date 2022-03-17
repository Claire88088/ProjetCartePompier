<?php

namespace App\Form;

use App\Entity\TypeElement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Nom du type d\'éléments ',
                'attr' => array(
                    'placeholder' => 'Entrez le nom du type d\'éléments'
                ))
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeElement::class,
        ]);
    }
}
