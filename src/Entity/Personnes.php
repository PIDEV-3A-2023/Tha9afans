<?php

namespace App\Entity;
use App\Repository\PersonnesRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personnes
 *
 * @ORM\Table(name="personnes", uniqueConstraints={@ORM\UniqueConstraint(name="unique_email", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\PersonnesRepository")
 */


#[ORM\Entity(repositoryClass: PersonnesRepository::class)]
class Personnes
{
    /**
     * @var int
     *o
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30)]
    private ?string $cin= null;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30)]
    private ?string $nom= null;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=20, nullable=false)
     */
    #[ORM\Column(length: 30)]
    private ?string $prenom=null;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=40, nullable=false)
     */
    #[ORM\Column(length: 40)]
    private ?string $email=null;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30)]
    private ?string $password=null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=30, nullable=true, options={"default"="utilisateur"})
     */
    #[ORM\Column(length: 30,nullable: True)]
    private ?string $role = 'utilisateur';

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30)]
    private ?string $telephone= null;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=30, nullable=false)
     */
    #[ORM\Column(length: 30)]
    private ?string $adresse=null;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="blob", length=0, nullable=true)
     */
    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private  $photo;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=true)
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datenaissance;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getCin(): ?string
    {
        return $this->cin;
    }

    /**
     * @param string|null $cin
     */
    public function setCin(?string $cin): void
    {
        $this->cin = $cin;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string|null
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * @param string|null $adresse
     */
    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    /**
     * @param \DateTimeInterface|null $datenaissance
     */
    public function setDatenaissance(?\DateTimeInterface $datenaissance): void
    {
        $this->datenaissance = $datenaissance;
    }




}
