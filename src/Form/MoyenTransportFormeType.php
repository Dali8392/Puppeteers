<?php

namespace App\Form;

use App\Entity\MoyenTransport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MoyenTransportFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorieMoyen', ChoiceType::class, [
                'choices' => [
                    'Terrestre' => 'Terrestre',
                    'Aérien' => 'Aérien',
                ],
                'placeholder' => 'Choose an option',
            ])
            ->add('typeMoyen', ChoiceType::class, [
                'choices' => [
                    'Avion' => 'Avion',
                    'Bus' => 'Bus',
                ],
                'placeholder' => 'Choose an option',
            ])
            ->add('idModele')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MoyenTransport::class,
        ]);
    }
}