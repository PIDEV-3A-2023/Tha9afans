<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="fkuserr", columns={"id_user"})})
 * @ORM\Entity
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    // private $id;
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id=null;


    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    // private $total;
    #[ORM\Column]
    private ?float $total=null;


    /**
     * @var \Personnes
     *
     * @ORM\ManyToOne(targetEntity="Personnes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    // private $idUser;
    #[ORM\ManyToOne(targetEntity: Personnes::class)]
    private ?Personnes $idUser=null;

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

    public function getIdUser(): ?Personnes
    {
        return $this->idUser;
    }

    public function setIdUser(?Personnes $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
