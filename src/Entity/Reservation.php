<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\Column(type: 'string', length: 20)]
    private $status; // new property for reservation status
    #[ORM\Column(type: 'string', length: 255, name: 'payment_info')]
    private $paymentInfo;
    #[ORM\Column(type: 'integer', name: 'total_price')]
    private $totalPrice;
    #[ORM\Column(type: 'string', length: 50, name: 'payment_status')]
    private $paymentStatus;
    #[ORM\Column(type: 'integer', name: 'nbr_billets')]
    private $nbrBillets;
    #[ORM\Column(type: 'integer', name: 'nbr_ticket_type_1_reserved')]
    private $nbrTicketType1Reserved;
    #[ORM\Column(type: 'integer', name: 'nbr_ticket_type_2_reserved')]
    private $nbrTicketType2Reserved;
    #[ORM\OneToMany(targetEntity: 'Billet', mappedBy: 'reservation', cascade: ['persist'])]
    private $billets;
    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private $user;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
    }


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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getNbrBillets(): ?int
    {
        return $this->nbrBillets;
    }

    public function setNbrBillets(int $nbrBillets): self
    {
        $this->nbrBillets = $nbrBillets;

        return $this;
    }

    public function getNbrTicketType1Reserved(): ?int
    {
        return $this->nbrTicketType1Reserved;
    }

    public function setNbrTicketType1Reserved(int $nbrTicketType1Reserved): self
    {
        $this->nbrTicketType1Reserved = $nbrTicketType1Reserved;

        return $this;
    }

    public function getNbrTicketType2Reserved(): ?int
    {
        return $this->nbrTicketType2Reserved;
    }

    public function setNbrTicketType2Reserved(int $nbrTicketType2Reserved): self
    {
        $this->nbrTicketType2Reserved = $nbrTicketType2Reserved;

        return $this;
    }

    public function getBillets(): Collection
    {
        return $this->billets;
    }

    public function addUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function addBillet(Billet $billet): self
    {
        if (!$this->billets->contains($billet)) {
            $this->billets->add($billet);
            $billet->setReservation($this);
        }

        return $this;
    }

    public function removeBillet(Billet $billet): self
    {
        if ($this->billets->removeElement($billet)) {
            // set the owning side to null (unless already changed)
            if ($billet->getReservation() === $this) {
                $billet->setReservation(null);
            }
        }

        return $this;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}