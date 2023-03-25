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

    #[ORM\ManyToOne(targetEntity: Personnes::class)]
    private ?Personnes $idUser=null;


}
