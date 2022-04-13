<?php

namespace App\Form;

use App\Entity\Doctor;
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

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => 'Nombre',
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => 'Apellido',
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Título',
            ])
            ->add('mobile', TextType::class, [
                'required' => false,
                'label' => 'Móvil',
                //'placeholder' => ['mob' => '+64341234567'],
            ])
            ->add('birthday', DateType::class, [
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Cumpleaños',
                ],
                'required' => true,
                'label' => 'Cumpleaños',
            ])
            ->add('bio', TextareaType::class, [
                'required' => false,
                'label' => 'Biográfico',
            ])
            ->add('section_id', TextType::class, [
                'required' => true,
                'label' => 'Sección',
            ])
            ->add('years', TextType::class, [
                'required' => true,
                'label' => 'Seniority',
            ])
            ->add('is_active', CheckboxType::class, [
                'required' => false,
                'label' => 'Activo'
            ])
            ->add('foto', FileType::class, [
                'required' => false,
                'label' => 'La foto',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
            ])
            //->add('created_at')
            //->add('modified_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
        ]);
    }
}
