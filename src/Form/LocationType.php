<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

use App\Entity\Location;
use App\Entity\Personne;
use App\Entity\Logement;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('datedebut', DateType::class, [
            'label' => 'Start Date', 
           
        ])
        ->add('datefin', DateType::class, [
            'label' => 'End Date',
           
        ])
        ->add('tarif', IntegerType::class, [
            'label' => 'Tarif',
          
        ])
        ->add('personne', EntityType::class, [
            'class' => Personne::class,
            'choice_label' => 'email',
            'label' => 'Locataire',
            'constraints' => [
                new NotBlank(),
               
            ],
            'placeholder' => 'Select a locataire', 
        ])
        ->add('logement', EntityType::class, [
            'class' => Logement::class,
            'choice_label' => 'adrl',
            'label' => 'Hebergement',
            'constraints' => [
                new NotBlank(),
                
            ],
            'placeholder' => 'Select a Hebergement', 
        ]);
    }
}