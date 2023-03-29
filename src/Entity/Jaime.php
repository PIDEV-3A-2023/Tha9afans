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
    private ?Evenement $Event=null;



    #[ORM\ManyToOne(inversedBy: 'jaimes')]
    private ?Personnes $User=null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Evenement
    {
        return $this->Event;
    }

    public function setEvent(?Evenement $Event): self
    {
        $this->Event = $Event;

        return $this;
    }

    public function getUser(): ?Personnes
    {
        return $this->User;
    }

    public function setUser(?Personnes $User): self
    {
        $this->User = $User;

        return $this;
    }


}
