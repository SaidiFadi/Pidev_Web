<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="idOffre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idoffre;

    /**
     * @var string
     *
     * @ORM\Column(name="nomOffre", type="string", length=15, nullable=false)
     */
    private $nomoffre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    private $datefin;

    /**
     * @var string
     *
     * @ORM\Column(name="typeOffre", type="string", length=0, nullable=false)
     */
    private $typeoffre;

    /**
     * @var int
     *
     * @ORM\Column(name="valeurOffre", type="integer", nullable=false)
     */
    private $valeuroffre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imageOffre", type="string", length=255, nullable=true)
     */
    private $imageoffre;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=0, nullable=false)
     */
    private $status;

    /**
     * @var \Personne
     *
     * @ORM\ManyToOne(targetEntity="Personne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    public function getIdoffre(): ?int
    {
        return $this->idoffre;
    }

    public function getNomoffre(): ?string
    {
        return $this->nomoffre;
    }

    public function setNomoffre(string $nomoffre): static
    {
        $this->nomoffre = $nomoffre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
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

    public function getTypeoffre(): ?string
    {
        return $this->typeoffre;
    }

    public function setTypeoffre(string $typeoffre): static
    {
        $this->typeoffre = $typeoffre;

        return $this;
    }

    public function getValeuroffre(): ?int
    {
        return $this->valeuroffre;
    }

    public function setValeuroffre(int $valeuroffre): static
    {
        $this->valeuroffre = $valeuroffre;

        return $this;
    }

    public function getImageoffre(): ?string
    {
        return $this->imageoffre;
    }

    public function setImageoffre(?string $imageoffre): static
    {
        $this->imageoffre = $imageoffre;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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
