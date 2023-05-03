<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //nullabale true
            ->add('nom' ,null,['required' => false,'label'=>'Recherche :','attr'=>['placeholder'=>"Nom de l'evenement"]])
        ;
    }

}
