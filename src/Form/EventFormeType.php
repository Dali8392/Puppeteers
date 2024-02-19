<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Import TextType
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; // Import DateTimeType
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // Import IntegerType
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank; // Import NotBlank constraint

class EventFormeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Name is required.']),
                ],
                'empty_data' => '',
            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Type is required.']),
                ],
                'empty_data' => '',
            ])
            ->add('date_debut', DateTimeType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Start Date is required.']),
                ],
            ])
            ->add('date_fin', DateTimeType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'End Date is required.']),
                ],
            ])
            ->add('max_participants', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Max Participants is required.']),
                ],
            ])
            ->add('budget_allocated', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Budget Allocated is required.']),
                ],
            ])
            ->add('userCreator', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Event Creator is required.']),
                ],
                'empty_data' => '',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Create Event',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
