<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierRepository;
use App\Repository\OffreRepository;
use App\Repository\PersonneRepository;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idPanier')]
    private ?int $idPanier = null;

    #[ORM\Column(name: 'datePanier', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datePanier = null;

    #[ORM\Column]
    private ?int $iduser = null;

    #[ORM\Column]
    private ?int $total = 00;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'Panier')]
    #[ORM\JoinColumn(name: 'idOffre', referencedColumnName: 'idOffre')]
    private ?Offre $idOffre = null;


    #[ORM\ManyToOne(targetEntity: Personne::class, inversedBy: 'Panier')]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    private ?Personne $id = null;



    public function getIdpanier(): ?int
    {
        return $this->idPanier;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getDatepanier(): ?\DateTimeInterface
    {
        return $this->datePanier;
    }

    public function setDatepanier(\DateTimeInterface $datepanier): static
    {
        $this->datePanier = $datepanier;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(?int $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdoffre(): ?Offre
    {
        return $this->idOffre;
    }

    public function setIdoffre(?Offre $idoffre): static
    {
        $this->idOffre = $idoffre;

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
}
