<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use App\Repository\TypeHebergementRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Range;

class HebergementFormeType extends AbstractType
{


    private $typeHebergementRepository;

    public function __construct(
        TypeHebergementRepository $typeHebergementRepository
    ) {
        $this->typeHebergementRepository = $typeHebergementRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $types = $this->typeHebergementRepository->findAll();

        $builder

            ->add('adresse')
            ->add('tarif')
            ->add('description')
            ->add('etat')
            ->add('dateDisponibilte')
            ->add('capacite', NumberType::class, [
                'constraints' => [
                    new Range(['min' => 0, 'max' => 20, 'minMessage' => 'capacity must be at least 0', 'maxMessage' => 'capacity cannot exceed 20'])
                ]
            ])
            ->add('typeHebergement', ChoiceType::class, [
                'choices' => $types,
                'choice_label' => function (?TypeHebergement $typeHebergement) {
                    return $typeHebergement ? $typeHebergement->getType() : '';
                },
                'placeholder' => 'Choose an option',
            ])
            ->add('image')
            ->add('name')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
            'csrf_protection' => false,
        ]);
    }
}
