<?php

// src/Entity/Search.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class Search
{


   private $etat;



    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

}