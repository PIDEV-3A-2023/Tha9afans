<?php

namespace App\Entity;

use App\Repository\BilletReserverRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BilletReserverRepository::class)]
class BilletReserver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $nombre = null;

    #[ORM\ManyToOne(targetEntity: 'Billet')]
    #[ORM\JoinColumn(name: 'id_billet', referencedColumnName: 'id')]
    private $billet;

    #[ORM\ManyToOne(targetEntity: 'Reservation')]
    #[ORM\JoinColumn(name: 'id_reservation', referencedColumnName: 'id')]
    private $reservation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getBillet(): ?Billet
    {
        return $this->billet;
    }

    public function setBillet(?Billet $billet): self
    {
        $this->billet = $billet;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }
}
