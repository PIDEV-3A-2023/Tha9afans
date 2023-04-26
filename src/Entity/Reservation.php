<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(type: 'date', name: 'date_reservation',nullable: true)]
    private $dateReservation;

    #[ORM\Column(type: 'string', length: 20)]
    private $status;

    #[ORM\Column(type: 'string', name: 'payment_info')]
    private $paymentInfo;

    #[ORM\Column(type: 'integer', name: 'total_price')]
    private $totalPrice;

    #[ORM\Column(type: 'string', length: 50, name: 'payment_status')]
    private $paymentStatus;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private $user;

    #[ORM\OneToMany(targetEntity: 'BilletReserver', mappedBy: 'reservation', cascade: ['persist'])]
    private $billetReservers;

    #[ORM\Column(type: 'string')]
    private $location;
    #[ORM\Column(type: 'string')]
    private $nom;
    #[ORM\Column(type: 'string')]
    private $prenom;
    #[ORM\Column(type: 'string')]
    private $email;
    #[ORM\Column(type: 'string')]
    private $telephone;
    #[ORM\Column(type: 'string')]
    private $address;

    private $nombreBillet;

    /**
     * @return mixed
     */
    public function getNombreBillet()
    {
        return $this->nombreBillet;
    }

    /**
     * @param mixed $nombreBillet
     */
    public function setNombreBillet($nombreBillet): void
    {
        $this->nombreBillet = $nombreBillet;
    }


    public function getNom()
    {
        return $this->nom;
    }
        public function setNom($nom): void
    {
        $this->nom = $nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }

      public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

      public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

     public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getAddress()
    {
        return $this->address;
    }

     public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }





    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->billetReservers = new ArrayCollection();
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

    /*public function addBillet(Billet $billet): self
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
    }*/

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, BilletReserver>
     */
    public function getBilletReservers(): Collection
    {
        return $this->billetReservers;
    }

    /*public function addBilletReserver(BilletReserver $billetReserver): self
    {
        if (!$this->billetReservers->contains($billetReserver)) {
            $this->billetReservers->add($billetReserver);
            $billetReserver->setReservation($this);
        }

        return $this;
    }*/

    /*public function removeBilletReserver(BilletReserver $billetReserver): self
    {
        if ($this->billetReservers->removeElement($billetReserver)) {
            // set the owning side to null (unless already changed)
            if ($billetReserver->getReservation() === $this) {
                $billetReserver->setReservation(null);
            }
        }

        return $this;
    }*/
}