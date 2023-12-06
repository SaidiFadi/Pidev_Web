<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Personne;
use App\Entity\Evenement;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      
        $builder
            ->add('titreevt', TextType::class)
            ->add('prixbillet')
            ->add('idevt', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'idevt', // Do not map this field to the entity
            ])
            ->add('id', EntityType::class, [
                'class' => Personne::class,
                'choice_label' => 'id', // Make sure this is necessary and intentional
            ])
            ->add('imageres');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
