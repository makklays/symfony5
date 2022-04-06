<?php

namespace App\Form;

use App\Entity\Animals;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnimalsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre',
            ])
            ->add('type_id', ChoiceType::class, [
                'choices' => [
                    '1. Gatos' => 1,
                    '2. Perros' => 2,
                    '3. Tortoises' => 3,
                ],
                'required' => true,
                'label' => 'Tipo de aniaml',
                'placeholder' => 'Eleji tipo de animales',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'tinymce textarea-big'],
                'label' => 'Descripción',
            ])
            ->add('img', FileType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Imagen',
                'mapped' => false,
                /*'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeType' => [
                            'application/image',
                        ],
                        'mimeTypeMessage' => 'Por favor, cargar Imagine valido',
                    ]),
                ],*/
            ])
            ->add('is_active', CheckboxType::class, [
                'required' => false,
                'label' => 'Activo',
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'Guardar',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animals::class,
        ]);
    }
}
