<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="res-billet", columns={"id_billet"}), @ORM\Index(name="res-owner", columns={"id_user"})})
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="date", nullable=false)
     */
    private $dateReservation;

    /**
     * @var bool
     *
     * @ORM\Column(name="isPaid", type="boolean", nullable=false)
     */
    private $ispaid;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_info", type="string", length=255, nullable=false)
     */
    private $paymentInfo;

    /**
     * @var \Billet
     *
     * @ORM\ManyToOne(targetEntity="Billet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_billet", referencedColumnName="id")
     * })
     */
    private $idBillet;

    /**
     * @var \Personnes
     *
     * @ORM\ManyToOne(targetEntity="Personnes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;


}
