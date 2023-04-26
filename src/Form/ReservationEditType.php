<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class ReservationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a first name',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'The first name should have at least {{ limit }} characters',
                        'maxMessage' => 'The first name should have at most {{ limit }} characters',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'first name'
                ]
            ])
            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a first name',
                    ]),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 15,
                        'minMessage' => 'The last name should have at least {{ limit }} characters',
                        'maxMessage' => 'The last name should have at most {{ limit }} characters',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'last name'
                ]
            ])
            ->add('email', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email address',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter an email address',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/',
                        'message' => 'Please enter a valid email address',
                    ]),
                ]
            ])
            ->add('telephone', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Phone number',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your phone number',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[2359]\d{7}$/',
                        'message' => 'Please enter a valid 8-digit phone number',
                    ]),
                ],
            ])
            ->add('address', ChoiceType::class, [
                'choices' => [
                    'Ariana' => 'Ariana',
                    'Tunis' => 'Tunis',
                    'Bizerte' => 'Bizerte',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select your current Location.',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'The address option cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class
        ]);
    }
}