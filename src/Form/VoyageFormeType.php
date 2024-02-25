<?php

namespace App\Form;

use App\Entity\Voyage;
use IntlCalendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
class VoyageFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart')
            ->add('destination')
            ->add('DateDep', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'attr' => array('class' => 'form-control', 'style' => 'line-height: 20px;')
            ))
            ->add('DateArr', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'attr' => array('class' => 'form-control', 'style' => 'line-height: 20px;')
            ))
            ->add('HeureDep', TimeType::class, [
                'label' => 'Time',
                'input'  => 'datetime',
                'widget' => 'single_text', 
                'empty_data' => '00:00'
            ])
            ->add('HeureArr', TimeType::class, [
                'label' => 'Time',
                'input'  => 'datetime',
                'widget' => 'single_text', 
                'empty_data' => '00:00'
            ])
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