<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
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
     * @var int
     *
     * @ORM\Column(name="idEvt", type="integer", nullable=false)
     */
    private $idevt;

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

    public function getIdbillet(): ?int
    {
        return $this->idbillet;
    }

    public function getIdevt(): ?int
    {
        return $this->idevt;
    }

    public function setIdevt(int $idevt): static
    {
        $this->idevt = $idevt;

        return $this;
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


}
