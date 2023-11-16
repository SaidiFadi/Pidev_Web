<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\AdresseRepository;
#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "street", type: "string", length: 255, nullable: true)]
    private $street;

    #[ORM\Column(name: "city", type: "string", length: 255, nullable: true)]
    private $city;

    #[ORM\Column(name: "postalCode", type: "string", length: 10, nullable: true)]
    private $postalcode;

    #[ORM\Column(name: "country", type: "string", length: 255, nullable: true)]
    private $country;

    #[ORM\ManyToOne(targetEntity: Personne::class)]
    #[ORM\JoinColumn(name: "personneId", referencedColumnName: "id")]
    private $personneid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setPostalcode(?string $postalcode): static
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPersonneid(): ?Personne
    {
        return $this->personneid;
    }

    public function setPersonneid(?Personne $personneid): static
    {
        $this->personneid = $personneid;

        return $this;
    }

}
