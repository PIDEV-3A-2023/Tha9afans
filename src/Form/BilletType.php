<?php

namespace App\Form;

use App\Entity\Billet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;

class BilletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a valid code',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 40,
                        'minMessage' => 'the code should have at least {{ limit }} characters',
                        'maxMessage' => 'The code should have at most {{ limit }} characters',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'code',
                    'id' => 'billet_code',
                ],
                'data' => $options['code_initial_value'],
            ])
            ->add('dateValidite', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a reservation date',
                    ]),
                    new Assert\GreaterThan([
                        'value' => 'today',
                        'message' => 'The date must be greater than today',
                    ]),
                ],
                'data' => $options['date_initial_value'],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'VIP' => "VIP",
                    'Normal' => "Normal",
                    'Etudiant' => "Etudiant",
                ],
                'expanded' => true, // display as radio buttons
                'label_attr' => [
                    'class' => 'radio-label', // set the class of the label
                ],
                'attr' => [
                    'class' => 'radio-input', // set the class of the input
                ],
            ])
            ->add('prix', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual(0),
                ],
                'data' => $options['prix_initial_value'],
                'disabled' => ($options['prix_initial_value'] === 0),
            ])
            ->add('nbrBilletAvailable', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nombre de billet ',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\GreaterThanOrEqual(0),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billet::class,
            'code_initial_value' => null,
            'date_initial_value' => null,
            'prix_initial_value' => null,
        ]);
    }
}
