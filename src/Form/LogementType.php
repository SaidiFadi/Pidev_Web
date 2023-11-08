<?php

namespace App\Form;

use App\Entity\Logement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank; // Correct the use statement here
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import the FileType

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adrl', null, [
                'constraints' => [
                    new NotBlank(), // Corrected the namespace
                    new Assert\Length(['min' => 5, 'max' => 255]),
                ],
            ])
            ->add('superfice', null, [
                'constraints' => [
                    new NotBlank(), // Corrected the namespace
                    new Assert\Range(['min' => 0]),
                ],
            ])
            ->add('loyer', null, [
                'constraints' => [
                    new NotBlank(), // Corrected the namespace
                    new Assert\Range(['min' => 0]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Appartment' => 'appartement',
                    'Maison' => 'maison',
                    'Hotel' => 'hotel',
                    'Studio' => 'studio'
                ],
                'constraints' => [
                    new NotBlank(), // Corrected the namespace
                ],
                'placeholder' => 'Select a type', // Optional placeholder text
            ])
            ->add('region', null, [
                'constraints' => [
                    new NotBlank(), // Corrected the namespace
                    // new Assert\Choice(['choices' => ['north', 'south', 'east', 'west']]),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false, // Set to false to allow null values
                'data_class' => null, // Set the data_class option to null
                'constraints' => [
                    new NotBlank(), // You can add NotBlank constraint if file is required
                    new Assert\File([
                        'maxSize' => '1024k', // Adjust as needed
                        'mimeTypes' => ['image/*'], // Allow only image file types
                    ]),
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}