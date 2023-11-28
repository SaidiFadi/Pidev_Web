<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Offre;
use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;




class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomoffre')
            ->add('description')
            ->add('datedebut')
            ->add('datefin')
            ->add('typeoffre', ChoiceType::class, [
                'choices' => [
                    'Réduction' => 'Réduction',
                    'Promotion' => 'Promotion',
                    'Package' => 'Package'
                ]
            ])
            ->add('valeuroffre')
            ->add('imageoffre', FileType::class, [
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new NotBlank(),
                    new Assert\File([
                        'maxSize' => '1024k',

                        'mimeTypes' => ['image/*'],
                    ]),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Activé' => 'Activé',
                    'Expirée' => 'Expirée',
                    'En_cours' => 'En_cours'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
