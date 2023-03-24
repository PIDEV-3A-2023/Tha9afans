<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date', name: 'date_reservation')]
    private $dateReservation;

    #[ORM\Column(type: 'boolean', name: 'isPaid')]
    private $isPaid;

    #[ORM\Column(type: 'string', length: 255, name: 'payment_info')]
    private $paymentInfo;

    #[ORM\ManyToOne(targetEntity: 'Billet')]
    #[ORM\JoinColumn(name: 'id_billet', referencedColumnName: 'id')]
    private $idBillet;

    #[ORM\ManyToOne(targetEntity: 'Personnes')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private $idUser;
}