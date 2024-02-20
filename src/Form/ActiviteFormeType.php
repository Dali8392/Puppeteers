<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiviteFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ville', TextType::class, [
                'label' => 'City:',
                'attr' => [
                    'class' => 'form-control mb-3 input-with-icon',
                    'style' => 'color: black;',
                ],
                'empty_data' => '',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Price:',
                'attr' => [
                    'class' => 'form-control mb-3 field-error input-with-icon',
                    'placeholder' => 'Enter price in €',
                    'style' => 'color: black;',
                ],
                'empty_data' => '',
            ])
            ->add('details', TextareaType::class, [
                'label' => 'Details:',
                'attr' => [
                    'class' => 'form-control mb-3 field-error input-with-icon',
                    'rows' => 5,
                    'style' => 'color: black;',
                ],
                'empty_data' => '',
            ])
            ->add('heure', TextType::class, [
                'label' => 'Time:',
                'attr' => [
                    'class' => 'form-control mb-3 field-error input-with-icon',
                    'style' => 'color: black;',
                ],
                'empty_data' => '',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg mb-3', 
                    'style' => 'padding-left: 30px; padding-right: 33px;', 
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
