<?php

namespace App\Form;

use App\Entity\Panierproduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PaymentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('card_number', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(4|5)\d{15}$/',
                        'message' => 'The card number must start with 4 or 5 and be 16 digits long.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => 16,
                    'class' => 'card-number-input'
                ]

            ])
            ->add('card_holder', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the name of the cardholder.'
                    ]),
                ],
                'attr' => [
                    'maxlength' => 50,
                    'class' => 'card-holder-input'
                ]
            ])
            ->add('expiration_month', ChoiceType::class, [
                'choices' => [
                    'month' => null,
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                    '05' => '05',
                    '06' => '06',
                    '07' => '07',
                    '08' => '08',
                    '09' => '09',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12'
                ],
                'attr' => [
                    'class' => 'month-input'
                ]
            ])
            ->add('expiration_year', ChoiceType::class, [

                'choices' => [
                    'year' => null,
                    '2021' => '2021',
                    '2022' => '2022',
                    '2023' => '2023',
                    '2024' => '2024',
                    '2025' => '2025',
                    '2026' => '2026',
                    '2027' => '2027',
                    '2028' => '2028',
                    '2029' => '2029',
                    '2030' => '2030'
                ],
                'attr' => [
                    'class' => 'year-input'
                ]
            ])
            ->add('cvv', IntegerType::class, [

                'attr' => [
                    'maxlength' => 4,
                    'class' => 'cvv-input'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'submit-btn'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Panierproduit::class,
        ]);
    }
}