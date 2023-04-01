<?php

namespace App\Form;

use App\Entity\CategorieEvenement;
use App\Entity\Evenement;
use App\Entity\Personnes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('date')
            ->add('createur',EntityType::class,['class'=>Personnes::class,'choice_label'=>'nom'/*,'multiple'=>'true'*/,'expanded'=>'true'])
            ->add('localisation')
            ->add('nbParticipants')
            ->add('nbAime')
            ->add('prix')
            ->add('Categorie',EntityType::class,['class'=>CategorieEvenement::class,'choice_label'=>'nom'/*,'multiple'=>'true'*/,'expanded'=>'true'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
