<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_USER' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'my-custom-class',
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
                    new Assert\LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'Le champ date de naissance doit être inférieure ou égale à aujourd\'hui',
                    ]),
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'image de profile',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('isBlocked', CheckboxType::class, [
                'label' => 'Bloqué',
                'required' => false,
            ])



        // check if the hide_password option is set to true
            ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password']
                ]);
        }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'hide_password' => false, // set the default value for the hide_password option to false
        ]);
    }
}
