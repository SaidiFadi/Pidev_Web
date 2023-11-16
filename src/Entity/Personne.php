<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?Date $datenaise = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $tele = null;

    #[ORM\Column(length: 255)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $ign = null;

    #[ORM\Column(length: 255)]
    private ?Boolean $isBanned;

    #[ORM\Column(length: 255)]
    private ?boolean $isVerified = null;
    
    #[ORM\Column(length: 255)]
    private ?string $pprofile = null;

    #[ORM\Column]
    private int $rolejavaClientId = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getDatenaise(): ?string
    {
        return $this->datenaise;
    }

    public function setDatenaise(string $datenaise): static
    {
        $this->datenaise = $datenaise;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(string $tele): static
    {
        $this->tele = $tele;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getIgn(): ?string
    {
        return $this->ign;
    }

    public function setIgn(string $ign): static
    {
        $this->ign = $ign;

        return $this;
    }

    public function getIsBanned(): ?string
    {
        return $this->isBanned;
    }

    public function setIsBanned(string $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getIsVerified(): ?string
    {
        return $this->isVerified;
    }

    public function setIsVerified(string $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPprofile(): ?string
    {
        return $this->pprofile;
    }

    public function setPprofile(string $pprofile): static
    {
        $this->pprofile = $pprofile;

        return $this;
    }

    public function getRolejavaClientId(): ?int
    {
        return $this->rolejavaClientId;
    }

    public function setRolejavaClientId(int $rolejavaClientId): static
    {
        $this->rolejavaClientId = $rolejavaClientId;

        return $this;
    }
}
