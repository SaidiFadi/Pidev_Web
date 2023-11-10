<?php

namespace App\Form;

use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('datenaise')
            ->add('adresse')
            ->add('tele')
            ->add('cin')
            ->add('ign')
            ->add('isBanned')
            ->add('isVerified')
            ->add('pprofile' , FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false,
                'required' => false,
            ])
            ->add('rolejavaClientId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
