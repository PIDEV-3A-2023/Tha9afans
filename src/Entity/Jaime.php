<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\JaimeRepository;

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



}
