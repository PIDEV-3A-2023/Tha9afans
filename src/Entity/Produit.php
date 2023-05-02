<?php

namespace App\Entity;

use App\Repository\ProduitRepository;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id =null;

    #[ORM\Column(length: 255)]
    private ?string $nom =null ;
    #[ORM\Column(length: 255)]
    private ?string $description =null ;


    #[ORM\Column]
    private ?int $libelle =null;



    #[ORM\Column]
    private ?float $prix= null;


    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private  $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="blob", length=65535, nullable=true)
     */
    //private $image;

    #[ORM\Column]
    private ?float $remise= null;
    #[ORM\Column]
    private ?float $rating= null;
    #[ORM\Column]
    private ?float $prixapresremise= null;
    #[ORM\Column]
    private ?int $qt= null;




    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "vendeur_id",referencedColumnName: "id")]
    private ?User $idVendeur;



    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "categorie_id",referencedColumnName: "id")]
    private ?Categorie $idCategorie;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLibelle(): ?int
    {
        return $this->libelle;
    }

    public function setLibelle(int $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }


    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPrixapresremise(): ?float
    {
        return $this->prixapresremise;
    }

    public function setPrixapresremise(float $prixapresremise): self
    {
        $this->prixapresremise = $this->getPrix() * (1 - $this->getRemise() / 100);

        return $this;
    }

    public function getIdVendeur(): ?User
    {
        return $this->idVendeur;
    }

    public function setIdVendeur(?User $idVendeur): self
    {
        $this->idVendeur = $idVendeur;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    public function __toString()
    {
        return(string) $this->getNom();
    }

    public function getQt(): ?int
    {
        return $this->qt;
    }

    public function setQt(int $qt): self
    {
        $this->qt = $qt;

        return $this;
    }


//    public function updatePrixapresremise(): void
//    {
//        $this->prixapresremise = $this->getPrix() * (1 - $this->getRemise() / 100);
//    }


}

