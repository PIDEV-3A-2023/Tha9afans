<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class QuizQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quiz', EntityType::class, [
                'class' => Quiz::class,
                'choice_label' => 'quizName',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please pick a quiz',
                    ]),
                ],

            ])
            ->add('questions', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'question',
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please pick a set of questions',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuizQuestion::class,
        ]);
    }
}


