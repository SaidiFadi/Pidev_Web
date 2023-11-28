<?php

namespace App\Entity;
use App\Repository\ReservationRepository;
use App\Entity\Evenement;
use App\Entity\Personne;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_billet")]
     private ?int $idBillet=null;


    #[ORM\Column]
    private ?string $titreevt;

    #[ORM\Column(name: "prixBillet", type: "float", nullable: false)]
    private ?float $prixbillet=0.0;

    #[ORM\Column(name:"imageRes", type:"string", length:255, nullable:false)]
    private ?string $imageres='';

    #[ORM\ManyToOne(targetEntity: Personne::class, inversedBy: "reservations")]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: "reservations")]
    #[ORM\JoinColumn(name: "idevt", referencedColumnName: "idevt")]
    private $idevt;
    public function __construct()
     {
         $this->reservations = new ArrayCollection();
         $this->prixbillet = 0.0;
     }

    public function getIdBillet(): ?int
    {
        return $this->idBillet;
    }

    public function getTitreevt(): ?string
{
    // Access the titreevt from the associated Evenement
    return $this->idevt ? $this->idevt->getTitreevt() : null;
}

public function setTitreevt(string $titreevt): static
{
    $this->titreevt = $titreevt;

    return $this;
}

public function getPrixbillet(): ?float
{
    return $this->prixbillet;
}

public function setPrixbillet(float $prixbillet): static
{   $this->prixbillet = $prixbillet;

    return $this;   }

    public function getImageres(): ?string
    {
        return $this->imageres;
    }

    public function setImageres(string $imageres): static
    {
        $this->imageres = $imageres;

        return $this;
    }

    public function getId(): ?Personne
    {
        return $this->id;
    }

    public function setId(?Personne $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIdevt(): ?Evenement
    {
        return $this->idevt;
    }

    public function setIdevt(?Evenement $idevt): static
    {
        $this->idevt = $idevt;

        return $this;
    }

}
