<?php

namespace App\Entity;

use App\Repository\PaymentSRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: PaymentSRepository::class)]
class PaymentS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cardNumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cardHolder = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $cvv = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }



    public function getCardHolder(): ?string
    {
        return $this->cardHolder;
    }

    public function setCardHolder(?string $cardHolder): self
    {
        $this->cardHolder = $cardHolder;

        return $this;
    }









    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(?int $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDAte(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }



}
