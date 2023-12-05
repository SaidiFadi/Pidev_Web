<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;



class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreevt')
            ->add('nomorg')
            ->add('descevt')
            ->add('hdEvt', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('hfEvt', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('adresseevt')
            ->add('typeevt')
            ->add('dateEvt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('vote')
            ->add('imageevt', FileType::class, [
                'required' => false,
                'data_class' => null,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Assert\File([
                        'maxSize' => '1024k',

                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Please upload a valid image file',

                    ]),
                ],
            ])
            ->add('videoevt', FileType::class, [
                'label' => 'Video',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '20204k',
                        'mimeTypes' => [
                            'video/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid video',
                    ])
                ],
            ])
            // ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
