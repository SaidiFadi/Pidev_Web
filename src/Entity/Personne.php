<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private  ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string  $nom = null;
    #[ORM\Column(length: 55)]
    private ?string  $prenom = null;

    #[ORM\Column(length: 55)]
    private ?string  $email = null;
    #[ORM\Column(length: 55)]
    private ?string  $roles = null;

    #[ORM\Column(length: 55)]
    private ?string  $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private  ?\DateTimeInterface $datenaise = null;

    #[ORM\Column(length: 55)]
    private ?string  $adresse = null;

    #[ORM\Column(length: 55)]
    private ?string  $tele = null;


    #[ORM\Column(length: 55)]
    private ?string  $cin = null;

    #[ORM\Column(length: 55)]
    private ?string  $ign = null;

    #[ORM\Column]
    private ?bool  $isBanned = null;

    #[ORM\Column]
    private ?bool  $isVerified = null;

    #[ORM\Column(length: 55)]
    private ?string  $pprofile = null;

    #[ORM\Column]
    private ?int $rolejavaClientId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(?string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getDatenaise(): ?\DateTimeInterface
    {
        return $this->datenaise;
    }

    public function setDatenaise(?\DateTimeInterface $datenaise): static
    {
        $this->datenaise = $datenaise;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(?string $tele): static
    {
        $this->tele = $tele;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getIgn(): ?string
    {
        return $this->ign;
    }

    public function setIgn(?string $ign): static
    {
        $this->ign = $ign;

        return $this;
    }

    public function isIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(?bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPprofile(): ?string
    {
        return $this->pprofile;
    }

    public function setPprofile(?string $pprofile): static
    {
        $this->pprofile = $pprofile;

        return $this;
    }

    public function getRolejavaClientId(): ?int
    {
        return $this->rolejavaClientId;
    }

    public function setRolejavaClientId(?int $rolejavaClientId): static
    {
        $this->rolejavaClientId = $rolejavaClientId;

        return $this;
    }
    
   
}
