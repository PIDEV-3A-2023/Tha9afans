<?php

namespace App\Form;

use App\Entity\Stripep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StripepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('card_number',null)
            ->add('card_holder')
            ->add('expiration_month')
            ->add('expiration_year')
            ->add('cvv')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stripep::class,
        ]);
    }
}
