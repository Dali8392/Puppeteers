<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class UserFormeType extends AbstractType
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
        
        ->add('lastName', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Last Name is required.']),
            ],
            'empty_data' => '',
        ])
        ->add('email', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Email is required.']),
                new Email(['message' => 'Invalid email format.']),
            ],
            'empty_data' => '',
        ])
        ->add('cin', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'CIN is required.']),
                new Length(['min' => 8, 'max' => 8, 'exactMessage' => 'CIN must be 8 characters long.']),
                new Type(['type' => 'numeric', 'message' => 'CIN must be numeric.']),
            ],
            'empty_data' => '',
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password'],
            'invalid_message' => 'The password fields must match.',
            'constraints' => [
                new NotBlank(['message' => 'Password is required.']),
                new Length(['min' => 8, 'minMessage' => 'Password must be at least {{ limit }} characters long.']),
                new Regex([
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                    'message' => 'Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.'
                ])
            ],
            'empty_data' => '',
        ])
        ->add('sign_up', SubmitType::class, [
            'label' => 'Sign Up'
        ]);
        
        
    }
    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}