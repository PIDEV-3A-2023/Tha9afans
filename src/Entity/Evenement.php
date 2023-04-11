<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{

    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    private ?string $nom = null;

    #[ORM\Column(length: 10000)]

    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?CategorieEvenement $categorie=null;



    #[ORM\Column(type: 'date')]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    private ?User $createur=null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $freeorpaid ;

    #[ORM\Column(type: 'boolean')]
    private ?bool $online ;

    #[ORM\Column(length: 255)]
    private ?string $link = null;




    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }


    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }


    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }


    public function getcreateur(): ?User
    {
        return $this->createur;
    }


    public function setCreateur(?User $createur): void
    {
        $this->createur = $createur;
    }


    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): void
    {
        $this->localisation = $localisation;
    }

    /**
     * @return bool|null
     */
    public function getFreeorpaid(): ?bool
    {
        return $this->freeorpaid;
    }

    /**
     * @param bool|null $freeorpaid
     */
    public function setFreeorpaid(?bool $freeorpaid): void
    {
        $this->freeorpaid = $freeorpaid;
    }

    /**
     * @return bool|null
     */
    public function getOnline(): ?bool
    {
        return $this->online;
    }

    /**
     * @param bool|null $online
     */
    public function setOnline(?bool $online): void
    {
        $this->online = $online;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }


    public function getCategorie(): ?CategorieEvenement
    {
        return $this->categorie;
    }


    public function setCategorie(?CategorieEvenement $categorie): void
    {
        $this->categorie = $categorie;
    }
}
