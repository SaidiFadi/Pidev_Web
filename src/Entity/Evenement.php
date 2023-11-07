<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEvt", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevt;

    /**
     * @var string
     *
     * @ORM\Column(name="titreEvt", type="string", length=255, nullable=false)
     */
    private $titreevt;

    /**
     * @var string
     *
     * @ORM\Column(name="nomOrg", type="string", length=255, nullable=false)
     */
    private $nomorg;

    /**
     * @var string
     *
     * @ORM\Column(name="descEvt", type="string", length=255, nullable=false)
     */
    private $descevt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hdEvt", type="time", nullable=false)
     */
    private $hdevt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hfEvt", type="time", nullable=false)
     */
    private $hfevt;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseEvt", type="string", length=255, nullable=false)
     */
    private $adresseevt;

    /**
     * @var string
     *
     * @ORM\Column(name="typeEvt", type="string", length=255, nullable=false)
     */
    private $typeevt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEvt", type="date", nullable=false)
     */
    private $dateevt;

    /**
     * @var int
     *
     * @ORM\Column(name="vote", type="integer", nullable=false)
     */
    private $vote;

    public function getIdevt(): ?int
    {
        return $this->idevt;
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

    public function getNomorg(): ?string
    {
        return $this->nomorg;
    }

    public function setNomorg(string $nomorg): static
    {
        $this->nomorg = $nomorg;

        return $this;
    }

    public function getDescevt(): ?string
    {
        return $this->descevt;
    }

    public function setDescevt(string $descevt): static
    {
        $this->descevt = $descevt;

        return $this;
    }

    public function getHdevt(): ?\DateTimeInterface
    {
        return $this->hdevt;
    }

    public function setHdevt(\DateTimeInterface $hdevt): static
    {
        $this->hdevt = $hdevt;

        return $this;
    }

    public function getHfevt(): ?\DateTimeInterface
    {
        return $this->hfevt;
    }

    public function setHfevt(\DateTimeInterface $hfevt): static
    {
        $this->hfevt = $hfevt;

        return $this;
    }

    public function getAdresseevt(): ?string
    {
        return $this->adresseevt;
    }

    public function setAdresseevt(string $adresseevt): static
    {
        $this->adresseevt = $adresseevt;

        return $this;
    }

    public function getTypeevt(): ?string
    {
        return $this->typeevt;
    }

    public function setTypeevt(string $typeevt): static
    {
        $this->typeevt = $typeevt;

        return $this;
    }

    public function getDateevt(): ?\DateTimeInterface
    {
        return $this->dateevt;
    }

    public function setDateevt(\DateTimeInterface $dateevt): static
    {
        $this->dateevt = $dateevt;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(int $vote): static
    {
        $this->vote = $vote;

        return $this;
    }


}
