<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a question',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Your question cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('answer', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an answer',
                    ]),

                ],
            ])
            ->add('timer', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a timer',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]*$/',
                        'message' => 'Please enter a valid number',
                    ]),
                ],
            ])
            ->add('firstPossibleAnswer', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the first possible answer',
                    ]),
                ],
            ])
            ->add('secondPossibleAnswer', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the second possible answer',
                    ]),
                ],
            ])
            ->add('thirdPossibleAnswer', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter the third possible answer',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'image Question',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please upload an image',
                    ]),
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
