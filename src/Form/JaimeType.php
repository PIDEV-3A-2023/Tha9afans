<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Jaime;
use App\Entity\Personnes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JaimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Event',EntityType::class,['class'=>Evenement::class,'choice_label'=>'nom','expanded'=>'true'])
            ->add('User',EntityType::class,['class'=>Personnes::class,'choice_label'=>'nom','expanded'=>'true'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jaime::class,
        ]);
    }
}
