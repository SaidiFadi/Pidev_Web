<?php

namespace App\Entity;
use App\Entity\Evenement;
use App\Entity\Personne;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idBillet = null;

    #[ORM\Column]
    private ?string $titreevt = null;

    #[ORM\Column(name: "prixBillet", type: "float", nullable: false)]
    private ?float $prixbillet;

    #[ORM\ManyToOne(targetEntity: Personne::class)]
    #[ORM\JoinColumn(name: "id", referencedColumnName: "id")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: "idEvt", referencedColumnName: "idEvt")]
    private $idEvt;

    public function getIdBillet(): ?int
    {
        return $this->idBillet;
    }

    public function getTitreevt(): ?string
    {
        return $this->titreevt;
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
    {
        $this->prixbillet = $prixbillet;

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

    public function getIdEvt(): ?Evenement
    {
        return $this->idEvt;
    }

    public function setIdEvt(?Evenement $idEvt): static
    {
        $this->idEvt = $idEvt;

        return $this;
    }

}
