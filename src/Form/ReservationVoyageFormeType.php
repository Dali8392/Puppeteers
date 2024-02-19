<?php

namespace App\Form;

use App\Entity\ReservationVoyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Range;
=======
>>>>>>> 42a7833db9154d776e467dfb64612089dc7ce596

class ReservationVoyageFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
        ->add('voyage')
        ->add('max', IntegerType::class, [
            'constraints' => [
                new Range([
                    'min' => 1,
                    'max' => 50,
                    'minMessage' => 'The maximum must be at least {{ limit }}.',
                    'maxMessage' => 'The maximum cannot be more than {{ limit }}.',
                ]),
            ],
        ])
        ->add('idUser')
        ->add('paiement')
    ;
}
=======
            ->add('max')
            ->add('idUser')
            ->add('paiement')
            ->add('submit', SubmitType::class)
        ;
    }
>>>>>>> 42a7833db9154d776e467dfb64612089dc7ce596

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationVoyage::class,
        ]);
    }
}