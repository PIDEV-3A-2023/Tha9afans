<?php

namespace App\Entity;
use App\Repository\GalerieRepository;
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
    private ?Evenement $idEvent=null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }


    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): void
    {
        $this->idEvent = $idEvent;
    }



}
