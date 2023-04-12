<?php
    namespace App\Form;
    use Symfony\Component\Validator\Constraints as Assert;
    use App\Entity\Categorie;
    use App\Entity\Produit;
    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Validator\Context\ExecutionContextInterface;


    class ProduitType extends AbstractType
    {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    $builder
        ->add('nom', null, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'Le nom ne doit contenir que des lettres et des espaces'
                ])
            ]
        ])

        ->add('description', null, [
    'constraints' => [
    new Assert\NotBlank(),
    ]
    ])
    ->add('libelle', null, [
    'constraints' => [
    new Assert\NotBlank(),
    ]
    ])
    ->add('prix', null, [
    'constraints' => [
    new Assert\NotBlank(),
    new Assert\Positive(),
    ]
    ])
    ->add('image', FileType::class, [
    'required' => false
    ])
        ->add('remise', null, [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Regex([
                    'pattern' => '/^(100|[1-9][0-9]?)$/',
                    'message' => 'La remise doit être un entier entre 0 et 100',
                ]),
            ],
        ])

        ->add('rating', null, [
    'constraints' => [
    new Assert\NotBlank(),
    new Assert\Regex([
    'pattern' => '/^\d+$/',
    'message' => 'Le rating doit être un nombre entier'
    ])
    ]
    ])
    ->add('prixapresremise')
    ->add('idVendeur', EntityType::class, [
    'class' => User::class,
    'choice_label' => 'nom', // ou une autre propriété de Joueur à afficher
    ])
    ->add('idCategorie', EntityType::class, [
    'class' =>Categorie::class,
    'choice_label' => 'nom', // ou une autre propriété de Joueur à afficher
    ])
    ->add('qt', null, [
    'constraints' => [
    new Assert\NotBlank(),
    new Assert\Positive(),
    ]
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
