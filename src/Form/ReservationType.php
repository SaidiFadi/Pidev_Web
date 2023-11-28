<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Personne;
use App\Entity\Evenement;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titreevt')
        ->add('prixbillet')
        ->add('idevt', EntityType::class, [
            'class' => Evenement::class,
            'choice_label' => 'idevt'])
        ->add('id', EntityType::class, [
            'class' => Personne::class,
            'choice_label' => 'id',
        ])
        ->add('imageres')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
