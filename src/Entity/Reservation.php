<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="idevt_fk", columns={"idEvt"}), @ORM\Index(name="iduser_fk", columns={"id"})})
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idBillet", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idbillet;

    /**
     * @var string
     *
     * @ORM\Column(name="titreEvt", type="string", length=255, nullable=false)
     */
    private $titreevt;

    /**
     * @var float
     *
     * @ORM\Column(name="prixBillet", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixbillet;

    /**
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEvt", referencedColumnName="idEvt")
     * })
     */
    private $idevt;

    /**
     * @var \Personne
     *
     * @ORM\ManyToOne(targetEntity="Personne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    public function getIdbillet(): ?int
    {
        return $this->idbillet;
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

    public function getIdevt(): ?Evenement
    {
        return $this->idevt;
    }

    public function setIdevt(?Evenement $idevt): static
    {
        $this->idevt = $idevt;

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
