<?php

namespace App\Form;

use App\Entity\Voyage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class VoyageFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart')
            ->add('destination')
            ->add('DateDep')
            ->add('DateArr')
            ->add('HeureDep')
            ->add('HeureArr')
            ->add('prix')
            ->add('NombrePlaceDispo')
            ->add('description' , TextareaType::class, [
                'attr' => ['rows' => 5], 
            ])
            ->add('moyenTransport')
            ->add('hebergement')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voyage::class,
        ]);
    }
}