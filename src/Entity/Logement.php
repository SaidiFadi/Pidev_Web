<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LogementRepository;
// #[ORM\Entity(repositoryClass: LogementRepository::class)]
/**
 * Logement
 *
 * @ORM\Table(name="logement")
 * @ORM\Entity
 */
class Logement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLogement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlogement;

    /**
     * @var string
     *
     * @ORM\Column(name="adrL", type="string", length=255, nullable=false)
     */
    private $adrl;

    /**
     * @var int
     *
     * @ORM\Column(name="superfice", type="integer", nullable=false)
     */
    private $superfice;

    /**
     * @var int
     *
     * @ORM\Column(name="loyer", type="integer", nullable=false)
     */
    private $loyer;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=20, nullable=false)
     */
    private $region;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;
    #[ORM\Entity(repositoryClass: LogementRepository::class)]
/* class Logement
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idlogement = null;
   

   
    #[ORM\Column(length: 255)]
    private ?string $adrl = null;

   
    #[ORM\Column]
    private ?int $superfice = null;
   


    #[ORM\Column]
    private ?int $loyer = null;
  

    
    #[ORM\Column(length: 255)]
    private ?string $type= null;
   

    
    #[ORM\Column(length: 20)]
    private ?string $region = null;
  

    
    #[ORM\Column(length: 255)]
    private ?string $image = null;
   
    #[ORM\OneToMany(mappedBy: 'logement', targetEntity: Location::class)]
    private Collection $location;
    public function __construct()
{
    $this->location = new ArrayCollection();
} */
    

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
