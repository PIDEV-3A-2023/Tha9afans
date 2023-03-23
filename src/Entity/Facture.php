<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture", indexes={@ORM\Index(name="commande", columns={"id_commende"})})
 * @ORM\Entity
 */
class Facture
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
     * @var \DateTime
     *
     * @ORM\Column(name="datefacture", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    // private $datefacture = 'CURRENT_TIMESTAMP';
    #[ORM\Column]
    private ?\DateTime $datefacture=null;


    /**
     * @var float
     *
     * @ORM\Column(name="tva", type="float", precision=10, scale=0, nullable=false)
     */
    // private $tva;
    #[ORM\Column]
    private ?float $tva=null;


    /**
     * @var string
     *
     * @ORM\Column(name="refrancefacture", type="string", length=255, nullable=false)
     */
    // private $refrancefacture;
    #[ORM\Column]
    private ?string $refrancefacture=null;


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
