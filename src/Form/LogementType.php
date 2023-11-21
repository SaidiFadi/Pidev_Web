<?php

namespace App\Form;

use App\Entity\Logement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank; 
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType; 


class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('adrl', null, [
                'label' => 'Adresse',
                
            ])
            ->add('superfice', null, [
                'label' => 'Superficie',
               
            ])
            ->add('loyer', null, [
                'label' => 'Loyer',
             
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Appartment' => 'appartement',
                    'Maison' => 'maison',
                    'Hotel' => 'hotel',
                    'Studio' => 'studio'
                ],
                'constraints' => [
                    new NotBlank(), 
                ],
                'placeholder' => 'Select a type', 
            ])
            ->add('region', ChoiceType::class, [
                'label' => 'Region',
                'choices' => [
                    'Ariana' => 'Ariana',
                    'Beja' => 'Beja',
                    'Ben Arous' => 'Ben Arous',
                    'Bizerte' => 'Bizerte',
                    'Tunis' => 'Tunis',
                    'Monastir' => 'Monastir',
                    'Sfax' => 'Sfax',
                    'Sousse' => 'Sousse',
                    'Tozeur' => 'Tozeur',
                    'Gabes' => 'Gabes',
                    'Gafsa' => 'Gafsa',
                    'Jendouba' => 'Jendouba',
                    'Kairouan' => 'Kairouan',
                    'Kasserine' => 'Kasserine',
                    'Kebili' => 'Kebili',
                    'Manouba' => 'Manouba',
                    'Medenine' => 'Medenine',
                    'Nabeul' => 'Nabeul',
                    'Siliana' => 'Siliana',
                    'Tataouine' => 'Tataouine',
                    'Zaghouan' => 'Zaghouan',
                ],
                'constraints' => [
                    new NotBlank(), 
                ],
                'placeholder' => 'Select a region', 
            ])
            
            ->add('image', FileType::class, [
                'required' => false, 
                'data_class' => null, 
                'constraints' => [
                    new NotBlank(), 
                    new Assert\File([
                        'maxSize' => '1024k', 
                        'mimeTypes' => ['image/*'],
                    ]),
                    
                ],
            ]);
            
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
            'images_directory' => null, 
        ]);
    }
}