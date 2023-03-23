<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fruserid1", columns={"id_user"})})
 * @ORM\Entity
 */
class Commande
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
     * @ORM\Column(name="dateCommande", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    // private $datecommande = 'CURRENT_TIMESTAMP';
    #[ORM\Column]
    #[ORM\GeneratedValue]// auto increment
    private ?\DateTime $datecommande=null;

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



}
