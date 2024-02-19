<?php

namespace App\Form;

use App\Entity\ReservationHebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< HEAD
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
=======
>>>>>>> 42a7833db9154d776e467dfb64612089dc7ce596

class ReservationHebergementFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
        ->add('hebergement')
        ->add('idUser')
        ->add('date', DateTimeType::class, [
            'widget' => 'single_text', // Afficher uniquement le champ de texte simple (sans widget de sélection)
        ])
        ->add('duree', DateTimeType::class, [
            'widget' => 'single_text', // Afficher uniquement le champ de texte simple (sans widget de sélection)
            
        ])
        ->add('max', IntegerType::class, [
            'constraints' => [
                new Range([
                    'min' => 1,
                    'max' => 10,
                    'minMessage' => 'The maximum must be at least {{ limit }}.',
                    'maxMessage' => 'The maximum cannot be more than {{ limit }}.',
                ]),
            ],
        ]);
            
=======
            ->add('idUser')
            ->add('date')
            ->add('duree')
            ->add('max')
            ->add('paiement')
            ->add('submit', SubmitType::class)
>>>>>>> 42a7833db9154d776e467dfb64612089dc7ce596
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationHebergement::class,
        ]);
    }
}