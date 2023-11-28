<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\PersonneRepository;


#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[UniqueEntity(fields: ["email"], message: "Il y a déjà un compte avec cette adresse e-mail")]
#[ORM\UniqueConstraint(name:'personne' ,columns:["email"])]

class Personne implements UserInterface, PasswordAuthenticatedUserInterface
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: "nom", type: "string", length: 55, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 55,
        maxMessage: "Le nom de Client ne peut pas dépasser {{ limit }} lettres."
        )]
    #[Assert\Regex(
            pattern: "/^[a-zA-Z ]+$/",
            message: "Le nom de Client ne peut être qu'alphabétique."
        )]
    private ?string $nom = null;

    #[ORM\Column(name: "prenom", type: "string", length: 55, nullable: true)]
    #[Assert\Length(max: 55,
    maxMessage: "Le prenom de Client ne peut pas dépasser {{ limit }} lettres."
        )]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z ]+$/",
        message: "Le prenom de Client ne peut être qu'alphabétique."
        )]
    private ?string $prenom = null;

    #[ORM\Column(name: "email", type: "string", length: 255, nullable: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(name: "roles", type: "string", length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $roles = null;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $password = null;

    #[ORM\Column(name: "dateNaise", type: "date", nullable: true)]
    #[Assert\NotBlank]
    #[Assert\LessThan(
        '-16 years',
        message: "Vous devez avoir au moins 16 ans pour vous inscrire."
    )]
    private ?\DateTimeInterface $datenaise = null;

    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $adresse = null;

    #[ORM\Column(name: "tele", type: "string", length: 15, nullable: true)]
    #[Assert\Length(max: 15)]
    private ?string $tele = null;

    #[ORM\Column(name: "cin", type: "string", length: 20, nullable: true)]
    #[Assert\Length(max: 14)]
    private ?string $cin = null;

    #[ORM\Column(name: "ign", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 12,
        maxMessage: "L'IGN ne peut pas dépasser {{ limit }} lettres."
    )]
        private ?string $ign = null;

    #[ORM\Column(name: "is_banned", type: "boolean", nullable: true)]
        private ?bool $isBanned = false;
    
    #[ORM\Column(name: "is_verified", type: "boolean", nullable: true)]
        private ?bool $isVerified = false;

    #[ORM\Column(name: "Pprofile", type: "string", length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
        private ?string $pprofile = null;

    #[ORM\Column(name: "roleJava_client_id", type: "integer", nullable: true, options: ["default" => "1"])]
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
        public function getSalt(): ?string
    {
        // Return the user's salt. If the user doesn't have a salt, return null.
        return null;
    }

    public function eraseCredentials(): void
    {
        // Erase the user's credentials. This could involve removing the password from memory or resetting it to a temporary value.
        $this->password = null;
    }

    public function getUsername(): string
    {
        // Return the user's username. This should be a unique identifier for the user.
        return $this->email;
    }



}