<?php

namespace App\Form;

use App\Entity\Billet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('dateValidite')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'VIP' => "VIP",
                    'Normal' => "Normal",
                    '3' => 3,
                ],
                'expanded' => true, // afficher les choix sous forme de boutons radio

            ])
            ->add('prix')
            ->add('nbrBilletAvailable')
//            ->add('evenement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billet::class,
        ]);
    }
}
