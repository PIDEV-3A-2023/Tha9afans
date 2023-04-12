<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('libelle')
            ->add('prix')
            ->add('image')
            ->add('remise')
            ->add('rating')
            ->add('prixapresremise')
            ->add('idVendeur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // ou une autre propriété de Joueur à afficher
            ])
            ->add('idCategorie', EntityType::class, [
                'class' =>Categorie::class,
                'choice_label' => 'nom', // ou une autre propriété de Joueur à afficher
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
