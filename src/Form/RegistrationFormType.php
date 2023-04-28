<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ email ne doit pas être vide',
                    ]), ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 6 caractères, des chiffres, des lettres minuscules et majuscules.'
                    ]),

                ],
            ])
            ->add('cin', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ cin ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'Le champ CIN doit contenir exactement 8 caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{8}$/',
                        'message' => 'Le champ CIN doit contenir uniquement des chiffres',
                    ]),
                ],
            ])

            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ nom ne doit pas être vide',
                    ]),
                ],
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ prénom ne doit pas être vide',
                    ]),
                ],
            ])
            ->add('telephone', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ téléphone ne doit pas être vide',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(\+216|00216)?[0-9]{8}$/',
                        'message' => 'Le numéro de téléphone doit être au format Tunisien : 8 chiffres commençant par +216 ou 00216',
                    ]),
                ],
            ])
            ->add('adresse', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ adresse ne doit pas être vide',
                    ]),
            ],
            ])
            ->add('datenaissance', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ date de naissance ne doit pas être vide',
                    ]),
                    
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Autre' => 'autre',
                ],
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ genre ne doit pas être vide',
                    ]),
                    new Assert\Choice(['choices' => ['homme', 'femme', 'autre']])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
