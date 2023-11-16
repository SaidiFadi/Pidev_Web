<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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


}
