<?php

namespace App\Form;

use App\Entity\PaymentS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PaymentSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your card number.'
                    ]),
                    new Regex([
                        'pattern' => '/^(4|5)\d{15}$/',
                        'message' => 'The card number must start with 4 or 5 and be 16 digits long.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => 16,
                    'minlength' => 16,
                    'class' => 'card-number-input'
                ]
            ])

            ->add('cardHolder', TextType::class, [
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
            ->add('cvv', IntegerType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the card code .'
                    ]),
                ],
                'attr' => [
                    'maxlength' => 3,
                    'class' => 'cvv-input',
                    'message' => 'The card code must be 3 digits long.',
                ]
            ])
            ->add('date', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the expiration date of the card.'
                    ]),
                ],
                'attr' => [
                    'class' => 'date-input'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentS::class,
        ]);
    }
}
