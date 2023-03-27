<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="Reservation")
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
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

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function isIspaid(): ?bool
    {
        return $this->ispaid;
    }

    public function setIspaid(bool $ispaid): self
    {
        $this->ispaid = $ispaid;

        return $this;
    }

    public function getPaymentInfo(): ?string
    {
        return $this->paymentInfo;
    }

    public function setPaymentInfo(string $paymentInfo): self
    {
        $this->paymentInfo = $paymentInfo;

        return $this;
    }

    public function getIdBillet(): ?Billet
    {
        return $this->idBillet;
    }

    public function setIdBillet(?Billet $idBillet): self
    {
        $this->idBillet = $idBillet;

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