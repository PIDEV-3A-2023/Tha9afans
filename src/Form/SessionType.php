<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',null,['constraints'=>[new Assert\NotBlank(message: 'Saisit le titre de la session.'),
                new Assert\Length(max:50,maxMessage: 'le titre de la session ne doit depasser {{ limit }} characters.')]
            ])
            ->add('description',null,['constraints'=>[new Assert\NotBlank(message: 'Saisit la description de la session.'),
                new Assert\Length(min:70,minMessage: 'la description doit contenir au moins {{ limit }} characters.')]
            ])
            ->add('parlant',null,['constraints'=>[new Assert\NotBlank(message: 'Saisit le nom du animateur .'),
                new Assert\Length(max:50,maxMessage: 'le nom du parlant ne doit depasser {{ limit }} characters.')]
            ])
            ->add('debit')
            ->add('fin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
