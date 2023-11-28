<?php

// src/Entity/Reclamation.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Commaintaire")
 */
class Commaintaire
{
   


        /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
 * @Assert\NotBlank(message="Le champ message doit être renseigné")
 
 * @ORM\Column(type="string", length=150)
 */
private $message;

    


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }




}
