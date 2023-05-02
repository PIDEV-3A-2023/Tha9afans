<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierRepository;

/**
 * Panier
 *
 * @ORM\Table(name="Panier")
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;

    #[ORM\Column]
    private ?float $total=null;

   /* #[ORM\ManyToOne(targetEntity: Personnes::class)]
    private ?Personnes $idUser=null;*/

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $idUser=null;

    #[ORM\Column(nullable: true)]
    private ?bool $ispayed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function isIspayed(): ?bool
    {
        return $this->ispayed;
    }

    public function setIspayed(?bool $ispayed): self
    {
        $this->ispayed = $ispayed;

        return $this;
    }






}
