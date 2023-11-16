<?php

namespace App\Entity;
use App\Repository\LogementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: LogementRepository::class)]

class Logement
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idLogement", type: "integer", nullable: false)]
    private $idlogement;

    #[ORM\Column(name: "adrL", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Adresse de logement is required")]
    private $adrl;

    #[ORM\Column(name: "superfice", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Superfice is required")]
    #[Assert\GreaterThan(value: 0, message: "Superfice should be greater than 0")]

    private $superfice;

    #[ORM\Column(name: "loyer", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Loyer is required")]
    #[Assert\GreaterThan(value: 0, message: "Loyer should be greater than 0")]

    private $loyer;

    #[ORM\Column(name: "type", type: "string", length: 0, nullable: false)]
   #[Assert\NotBlank(message: "Type is required")]
    private $type;

    #[ORM\Column(name: "region", type: "string", length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Region is required")]
    private $region;

    #[ORM\Column(name: "image", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Image is required")]
    #[Assert\File(maxSize: "5M", mimeTypes: ["image/jpeg", "image/png"], mimeTypesMessage: "Please upload a valid image (JPEG or PNG)")]

    private $image;

    public function getIdlogement(): ?int
    {
        return $this->idlogement;
    }

    public function getAdrl(): ?string
    {
        return $this->adrl;
    }

    public function setAdrl(string $adrl): static
    {
        $this->adrl = $adrl;

        return $this;
    }

    public function getSuperfice(): ?int
    {
        return $this->superfice;
    }

    public function setSuperfice(int $superfice): static
    {
        $this->superfice = $superfice;

        return $this;
    }

    public function getLoyer(): ?int
    {
        return $this->loyer;
    }

    public function setLoyer(int $loyer): static
    {
        $this->loyer = $loyer;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
