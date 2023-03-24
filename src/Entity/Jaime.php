<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\JaimeRepository;

/**
 * Jaime
 *
 * @ORM\Table(name="Jaime")
 * @ORM\Entity(repositoryClass="App\Repository\JaimeRepository")
 */
#[ORM\Entity(repositoryClass: JaimeRepository::class)]
class Jaime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]// auto increment
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'jaimes')]
    private ?Evenement $idEvent=null;



    #[ORM\ManyToOne(inversedBy: 'jaimes')]
    private ?Personnes $idUser=null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): void
    {
        $this->idEvent = $idEvent;
    }

    public function getIdUser(): ?Personnes
    {
        return $this->idUser;
    }


    public function setIdUser(?Personnes $idUser): void
    {
        $this->idUser = $idUser;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): self
    {
        $this->idEvent = $idEvent;

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
