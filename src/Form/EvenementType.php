<?php

namespace App\Form;

use App\Entity\CategorieEvenement;
use App\Entity\Evenement;
use App\Entity\User;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null,['constraints'=>[new Assert\NotBlank(message: 'Saisit le nom evenement.'),
                new Assert\Length(max:50,maxMessage: 'le nom evenement ne doit depasser {{ limit }} characters.')]
            ])
            ->add('description',null,['constraints'=>[new Assert\NotBlank(message: 'Saisit le nom evenement.'),
               new Assert\Length(min:100,minMessage: 'la description doit contenir au moins {{ limit }} characters.')]
            ])
            ->add('date',null,['constraints'=>[new Assert\NotBlank(message: 'Saisit la date evenement.'),
                new Assert\GreaterThanOrEqual('today', message: "la date doit etre superieur a la date d'aujourd'hui.")]
            ])
            ->add('localisation')
            ->add('createur',EntityType::class,['class'=>User::class,'choice_label'=>'nom'])
            ->add('freeorpaid')
            ->add('online',) //choices true or false
            ->add('link',null,['constraints'=>[new Assert\NotBlank(message: "Saisit le lien de l'evenement"),
                new Assert\Url(message: "le lien doit etre une URL valide.")]
            ])
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
