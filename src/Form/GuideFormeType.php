<?php

namespace App\Form;

use App\Entity\Guide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuideFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('cin', TextType::class, [
                'label' => 'CIN:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('role', TextType::class, [
                'label' => 'Role:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('langue', TextType::class, [
                'label' => 'Language:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('ville', TextType::class, [
                'label' => 'City:',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'style' => 'color: black;',
                ],
                'empty_data' => '', // Transform empty data to an empty string
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-primary btn-lg mb-3', // make the button bigger
                    'style' => 'padding-left: 30px; padding-right: 33px;', // make the button wider
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);
    }
}
