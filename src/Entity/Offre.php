<?php

namespace App\Entity;

use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffreRepository;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'idOffre')]
    private  ?int $idoffre = null;


    #[Assert\NotBlank(message: "Le nom de l'offre  ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: "Le nom ne peut contenir que des lettres"
    )]
    #[ORM\Column(name: 'nomOffre', type: 'string', length: 255, nullable: false)]
    private $nomoffre;

    #[Assert\NotBlank(message: "La description ne peut pas être vide")]
    #[ORM\Column(name: 'description', type: 'string', length: 255, nullable: false)]
    #[Assert\Length(
        min: 6,
        minMessage: 'Le description doit contenir au moins {{ limit }} caractères.'
    )]
    private  $description;

    #[Assert\NotBlank(message: 'La datedebut ne peut pas être vide.')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today", message: "please  saisir une date égale ou suppérieure  à celle d'aujourd'hui .")]
    private  ?\DateTimeInterface $datedebut = null;

    #[Assert\NotBlank(message: 'La datefin  ne peut pas être vide.')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private  ?\DateTimeInterface $datefin = null;

    #[Assert\NotBlank(message: "Le type de l'offre  ne peut pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string  $typeoffre = null;

    #[Assert\NotBlank(message: "Le valeur de l'offre  ne peut pas être vide")]
    #[ORM\Column]
    private ?int $valeuroffre = null;

    #[Assert\NotBlank(message: "Le  image de l'offre  ne peut pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $imageoffre = null;

    #[Assert\NotBlank(message: "Le staus de l'offre  ne peut pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $status = null;



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
}
