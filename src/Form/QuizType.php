<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quizName', TextType::class, [
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
            ->add('nbrQuestions',  NumberType::class, [
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
            ->add('quizCover', FileType::class, [
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
            ->add('quizDescription', TextareaType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
