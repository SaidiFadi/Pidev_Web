<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; // Importez le type TextareaType

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder





       // ->add('etat')
      //  ->add('idUser')

            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Sport' => 'sport',
                    'Event' => 'event',
                    'Soirée' => 'soiree',
                ],
                'placeholder' => 'Sélectionnez un type',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 10], // Définissez le nombre de lignes souhaité
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
