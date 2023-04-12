<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BilletRepository;
/**
 * Billet
 *
 * @ORM\Table(name="Billet")
 * @ORM\Entity(repositoryClass="App\Repository\BilletRepository")
 */
#[ORM\Entity(repositoryClass: BilletRepository::class)]

class Billet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'The code cannot be blank.')]
    #[Assert\Length(max:150,maxMessage: 'The code cannot exceed {{ limit }} characters.')]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull(message: 'The date cannot be null.')]
    #[Assert\GreaterThan('today', message: 'The date should be greater than or equal to today.')]
    private ?\DateTimeInterface $dateValidite = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull(message: 'The price cannot be null.')]
    #[Assert\PositiveOrZero(message: 'The price should be a positive number.')]
    private $prix;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: "The type cannot be blank.")]
    #[Assert\Length(max: 50, maxMessage: "The type cannot exceed {{ limit }} characters.")]
    private $type; // new property for billet type
    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message: "The number of available tickets cannot be null.")]
    #[Assert\PositiveOrZero(message: "The number of available tickets should be a positive number or zero.")]
    private $nbrBilletAvailable; // new property for available billets

    #[ORM\ManyToOne(targetEntity: 'Evenement', inversedBy: 'billets')]
    #[ORM\JoinColumn(name: 'id_evenement', referencedColumnName: 'id')]
    private $evenement;

    #[ORM\OneToMany(targetEntity: 'BilletReserver', mappedBy: 'billet', cascade: ['persist'])]
    #[Assert\NotNull]
    private $billetReservers;

    public function __construct()
    {
        $this->billetReservers = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDateValidite(): ?\DateTimeInterface
    {
        return $this->dateValidite;
    }

    public function setDateValidite(?\DateTimeInterface $dateValidite): self
    {
        $this->dateValidite = $dateValidite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
    public function getNbrBilletAvailable(): ?int
    {
        return $this->nbrBilletAvailable;
    }

    public function setNbrBilletAvailble(int $nbrBilletAvailable): self
    {
        $this->nbrBilletAvailable = $nbrBilletAvailable;

        return $this;
    }


    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function setNbrBilletAvailable(int $nbrBilletAvailable): self
    {
        $this->nbrBilletAvailable = $nbrBilletAvailable;

        return $this;
    }

    /**
     * @return Collection<int, BilletReserver>
     */
    public function getBilletReservers(): Collection
    {
        return $this->billetReservers;
    }

    public function addBilletReserver(BilletReserver $billetReserver): self
    {
        if (!$this->billetReservers->contains($billetReserver)) {
            $this->billetReservers->add($billetReserver);
            $billetReserver->setBillet($this);
        }

        return $this;
    }

    public function removeBilletReserver(BilletReserver $billetReserver): self
    {
        if ($this->billetReservers->removeElement($billetReserver)) {
            // set the owning side to null (unless already changed)
            if ($billetReserver->getBillet() === $this) {
                $billetReserver->setBillet(null);
            }
        }

        return $this;
    }

}
