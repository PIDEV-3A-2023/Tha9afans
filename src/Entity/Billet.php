<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'billet')]
#[ORM\Entity]
class Billet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 150)]
    private ?string $code = null;

    #[ORM\Column(type: 'date')]
    private $dateValidite;

    #[ORM\Column(type: 'float')]
    private $prix;

    #[ORM\ManyToOne(targetEntity: 'Evenement')]
    #[ORM\JoinColumn(name: 'id_evenement', referencedColumnName: 'id')]
    private $idEvenement;

}
