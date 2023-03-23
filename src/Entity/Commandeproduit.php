<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandeproduit
 *
 * @ORM\Table(name="commandeproduit", indexes={@ORM\Index(name="fkcommande", columns={"id_commende"})})
 * @ORM\Entity
 */
class Commandeproduit
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
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    // private $quantite;
    #[ORM\Column]
    private ?int $quantite=null;


    /**
     * @var int
     *
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     */
    //private $idProduit;
    #[ORM\Column]
    private ?int $idProduit=null;


    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commende", referencedColumnName="id")
     * })
     */
    // private $idCommende;
    #[ORM\ManyToOne(targetEntity: Commande::class)]
    private ?Commande $idCommende=null;



}
