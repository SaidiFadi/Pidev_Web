<?php

namespace App\Entity;
use App\Repository\LocationRepository;

use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: LocationRepository::class)]
/**
 * Location
 *
 * @ORM\Table(name="location", indexes={@ORM\Index(name="fk1", columns={"logement"}), @ORM\Index(name="fk15", columns={"personne"})})
 * @ORM\Entity
 */
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idLocation", type: "integer", nullable: false)]
    private $idlocation;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(message: "Start date is required")]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank(message: "End date is required")]
    private ?\DateTimeInterface $datefin = null;

    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Adresse de logement is required")]
    private ?int $tarif = null;

    #[ORM\ManyToOne(targetEntity: Personne::class)]
     #[ORM\JoinColumn(name: "personne", referencedColumnName: "id")]
    
    private $personne;

    #[ORM\ManyToOne(targetEntity: Logement::class)]
    #[ORM\JoinColumn(name: "logement", referencedColumnName: "idLogement")]
    
    private $logement;

    public function getIdlocation(): ?int
    {
        return $this->idlocation;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;
        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;
        return $this;
    }
    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(int $tarif): static
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): static
    {
        $this->personne = $personne;

        return $this;
    }

    public function getLogement(): ?Logement
    {
        return $this->logement;
    }

    public function setLogement(?Logement $logement): static
    {
        $this->logement = $logement;

        return $this;
    }
}