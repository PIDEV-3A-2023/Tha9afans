<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question')
            ->add('answer')
            ->add('timer')
            ->add('firstPossibleAnswer')
            ->add('secondPossibleAnswer')
            ->add('thirdPossibleAnswer')
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*', 'class' => 'file-input']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
