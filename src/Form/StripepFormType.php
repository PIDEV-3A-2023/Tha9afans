<?php

namespace App\Form;

use App\Entity\Stripep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;


class StripepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //card_number NotBlank Regex 16 digits start with 4 or 5
            ->add('card_number', null, [
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

            //card_holder NotBlank 50 characters max length
            ->add('card_holder', null, [
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

            ->add('expiration_year', ChoiceType::class, [
                'choices' => [
                    'year' => null,
                    '21' => '21',
                    '22' => '22',
                    '23' => '23',
                    '24' => '24',
                    '25' => '25',
                    '26' => '26',
                    '27' => '27',
                    '28' => '28',
                    '29' => '29',
                    '30' => '30'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a year.'
                    ]),
                ],
                'attr' => [
                    'class' => 'year-input'
                ]
            ])
           /* ->add('expiration_month')*/
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
               'constraints' => [
                   new NotBlank([
                       'message' => 'Please select a month.'
                   ]),
               ],
               'attr' => [
                   'class' => 'month-input'
               ]
           ])





    /*        ->add('expiration_year', ChoiceType::class, [
                'label' => 'Expiration Year',
                'required' => true,
                'choices' => array_combine(range(date('Y'), date('Y') + 10), range(date('Y'), date('Y') + 10)),
                'attr' => ['class' => 'year-input'],
                'constraints' => [
                    new NotBlank(['message' => 'Please select the expiration year.']),
                ],
                'data' => date('Y'),
            ])*/


            ->add('cvv', TextType::class, [
                'label' => 'CVV',
                'required' => true,
                'attr' => ['class' => 'cvv-input'],
                'constraints' => [
                    new NotBlank(['message' => 'Please enter the CVV.']),
                    new Length([
                        'min' => 3,
                        'max' => 3,
                        'minMessage' => 'Please enter a valid CVV.',
                        'maxMessage' => 'Please enter a valid CVV.',
                    ]),
                ],
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stripep::class,
        ]);
    }
}
