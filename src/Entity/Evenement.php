<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $idevt = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 5, minMessage: "Le titre doit être composé au minimum de 5 caractères")]

    private ?string $titreevt = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le nom de l"organisateur ne doit contenir que des lettres'
    )]

    private ?string $nomorg = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10,minMessage: "La description doit etre composé au minimum de 10 carateres")]

    private ?string $descevt = null; 

    #[ORM\Column(name: "hdEvt", type: "time", nullable: false)]
     private ?\DateTime $hdEvt;

    #[ORM\Column(name: "hfEvt", type: "time", nullable: false)]
    #[Assert\GreaterThan(propertyPath: "hdEvt", message: "L'heure de fin doit être supérieure à l'heure de début .")]

    private ?\DateTime $hfEvt;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:' L"adresse est obligatoire')]
     private ?string $adresseevt;

     #[ORM\Column(type: 'string', length: 255, nullable: false, options: ['default' => 'Evènement Public'])]

     private ?string $typeevt = null; 

     #[ORM\Column(type: Types::DATE_MUTABLE)]
     #[Assert\GreaterThanOrEqual("today", message: "Veuillez saisir une date égale ou ultérieure à celle d'aujourd'hui .")]

     private ?\DateTimeInterface $dateevt = null;

     #[ORM\Column]

     private ?int $vote = null;

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

     public function getHdEvt(): ?\DateTimeInterface
     {
         return $this->hdEvt;
     }

     public function setHdEvt(\DateTimeInterface $hdEvt): static
     {
         $this->hdEvt = $hdEvt;

         return $this;
     }

     public function getHfEvt(): ?\DateTimeInterface
     {
         return $this->hfEvt;
     }

     public function setHfEvt(\DateTimeInterface $hfEvt): static
     {
         $this->hfEvt = $hfEvt;

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
