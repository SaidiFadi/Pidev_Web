<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Personne
 *
 * @ORM\Table(name="personne", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity
 */
class Personne
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=55, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=55, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="roles", type="string", length=255, nullable=true)
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateNaise", type="date", nullable=true)
     */
    private $datenaise;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tele", type="string", length=15, nullable=true)
     */
    private $tele;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cin", type="string", length=20, nullable=true)
     */
    private $cin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ign", type="string", length=255, nullable=true)
     */
    private $ign;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_banned", type="boolean", nullable=true)
     */
    private $isBanned;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_verified", type="boolean", nullable=true)
     */
    private $isVerified;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Pprofile", type="string", length=255, nullable=true)
     */
    private $pprofile;

    /**
     * @var int|null
     *
     * @ORM\Column(name="roleJava_client_id", type="integer", nullable=true, options={"default"="1"})
     */
    private $rolejavaClientId = 1;

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
