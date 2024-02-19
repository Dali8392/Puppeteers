<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteFormeType extends AbstractType
{
 public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('ville', TextType::class, [
            'label' => 'City:', 
            'attr' => [
                'class' => 'form-control mb-3',
                'style' => 'color: black;', 
            ],
        ])
        ->add('prix', NumberType::class, [
            'label' => 'Price:',
            'attr' => [
                'class' => 'form-control mb-3 field-error',
                'placeholder' => 'Enter price in €',
                'style' => 'color: black;',
            ],
        ])
        ->add('details', TextareaType::class, [
            'label' => 'Details:',
            'attr' => [
                'class' => 'form-control mb-3 field-error',
                'rows' => 5,
                'style' => 'color: black;',
            ],
        ])
        ->add('heure', TextType::class, [
            'label' => 'Time:',
            'attr' => [
                'class' => 'form-control mb-3 field-error',
                'style' => 'color: black;',
                
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => ' Submit',
            'attr' => [
                'class' => 'btn btn-primary mt-3', 
            ],
        ]);
}
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
