<?php

namespace App\Entity;

use App\Repository\StripepRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StripepRepository::class)]
class Stripep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $card_number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $card_holder = null;

    #[ORM\Column(nullable: true)]
    private ?int $expiration_month = null;

    #[ORM\Column(nullable: true)]
    private ?int $expiration_year = null;

    #[ORM\Column(length: 3,nullable: true)]
    private ?string $cvv = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?string
    {
        return $this->card_number;
    }

    public function setCardNumber(?string $card_number): self
    {
        $this->card_number = $card_number;

        return $this;
    }

    public function getCardHolder(): ?string
    {
        return $this->card_holder;
    }

    public function setCardHolder(?string $card_holder): self
    {
        $this->card_holder = $card_holder;

        return $this;
    }

    public function getExpirationMonth(): ?int
    {
        return $this->expiration_month;
    }

    public function setExpirationMonth(?int $expiration_month): self
    {
        $this->expiration_month = $expiration_month;

        return $this;
    }

    public function getExpirationYear(): ?int
    {
        return $this->expiration_year;
    }

    public function setExpirationYear(?int $expiration_year): self
    {
        $this->expiration_year = $expiration_year;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }
}
