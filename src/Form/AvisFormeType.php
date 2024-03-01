<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AvisFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', NumberType::class, [
                'constraints' => [
                    new Range(['min' => 0, 'max' => 5, 'minMessage' => 'Note must be at least 0', 'maxMessage' => 'Note cannot exceed 5'])
                ]
            ])
            ->add('commentaire',TextareaType::class,[
                'label'=>'Votre commentaire',
                'attr'=>[
                    'class' => 'form-control'
                ]
            
            ])
            ->add('hebergement')
            ->add('email',EmailType::class,[
                'label'=>'Votre e-mail',
                'attr'=>[
                    'class' => 'form-control'
                ]
            
            ])
            ->add('submit', SubmitType::class)
            
        ;
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
            'csrf_protection' => false,
        ]);
    }
}