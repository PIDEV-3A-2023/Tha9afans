<?php

namespace App\Form;

use App\Entity\Personnes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PersonnesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('nom',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('prenom',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('email',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('password',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('role',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('telephone',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('adresse',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*', 'class' => 'file-input'],
            ])
            ->add('datenaissance',null,
                ['attr' =>[
                    'class'=> 'form-input'
                ],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnes::class,
        ]);
    }
}
