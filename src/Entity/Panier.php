<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", indexes={@ORM\Index(name="idOffre", columns={"idOffre"}), @ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="idPanier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idpanier;

    /**
     * @var int
     *
     * @ORM\Column(name="total", type="integer", nullable=false)
     */
    private $total;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePanier", type="date", nullable=false)
     */
    private $datepanier;

    /**
     * @var int|null
     *
     * @ORM\Column(name="iduser", type="integer", nullable=true)
     */
    private $iduser;

    /**
     * @var \Personne
     *
     * @ORM\ManyToOne(targetEntity="Personne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * @var \Offre
     *
     * @ORM\ManyToOne(targetEntity="Offre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idOffre", referencedColumnName="idOffre")
     * })
     */
    private $idoffre;

    public function getIdpanier(): ?int
    {
        return $this->idpanier;
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
        return $this->datepanier;
    }

    public function setDatepanier(\DateTimeInterface $datepanier): static
    {
        $this->datepanier = $datepanier;

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

    public function getId(): ?Personne
    {
        return $this->id;
    }

    public function setId(?Personne $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIdoffre(): ?Offre
    {
        return $this->idoffre;
    }

    public function setIdoffre(?Offre $idoffre): static
    {
        $this->idoffre = $idoffre;

        return $this;
    }


}
