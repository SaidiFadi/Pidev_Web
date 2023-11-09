<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EntityType; // Make sure this line is present
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Location;
use App\Entity\Personne;
use App\Entity\Logement;


class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datedebut', DateType::class)
            ->add('datefin', DateType::class)
            ->add('tarif', IntegerType::class)
            ->add('personne', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => 'email', // adjust to the property you want to display
            ])
            ->add('logement', EntityType::class, [
                'class' => Logement::class,
                'choice_label' => 'adrl', // adjust to the property you want to display
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}