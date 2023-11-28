<?php

namespace App\Form;

use App\Entity\Commaintaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('message', TextareaType::class, [
            'attr' => [
                'rows' => 10,
                'novalidate' => 'novalidate', // Désactiver la validation côté client
            ],
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commaintaire::class,
        ]);
    }
}
