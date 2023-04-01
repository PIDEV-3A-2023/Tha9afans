<?php

namespace App\Entity;


use App\Repository\GalerieRepository;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;
/**
 * Galerie
 *
 * @ORM\Table(name="Galerie")
 * @ORM\Entity(repositoryClass="App\Repository\GalerieRepository")
 */
#[ORM\Entity(repositoryClass: GalerieRepository::class)]
class Galerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'blob')]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'galeries')]
    private ?Evenement $event = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEvent(): ?Evenement
    {
        return $this->event;
    }

    public function setEvent(?Evenement $event): self
    {
        $this->event = $event;

        return $this;
    }

}
