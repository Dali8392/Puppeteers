<?php

namespace App\Form;

use App\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaiementFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('montant', null, [
            'constraints' => [
                new PositiveOrZero([
                    'message' => 'Le montant doit être un nombre positif ou zéro.',
                ]),
            ],
            'attr' => [
                'pattern' => '\d*',
                'title' => 'Le montant doit être un nombre positif ou zéro.',
            ],
            ])
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
             // Afficher uniquement le champ de texte simple (sans widget de sélection)
             'constraints' => [
                new NotBlank(['message' => 'date is required.']),
            ],
            'empty_data' => null,
        
            ])
            ->add('methode', ChoiceType::class, [
                'choices' => [
                    'Credit Card' => 'credit_card',
                    'Paypal' => 'paypal',
                ],
                'placeholder' => 'Select Payment Method',
                'required' => true,
            ]);
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}