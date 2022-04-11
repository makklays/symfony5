<?php

namespace App\Form;

use App\Entity\Paciente;
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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Doctor;

class PacienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'Nombre',
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'Apellido',
            ])
            ->add('mobile', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'Movile',
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'Corréo',
            ])
            ->add('nie', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'NIE',
            ])
            ->add('birthday', DateType::class, [
                'format' => 'y M d',
                'label' => 'Cumpleaños',
                'attr' => ['class' => 'input-group'],
                /*'days' => range(1,31),*/
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Cumpleaños',
                ],
                /*'attr' => ['style' => 'width:200px; display:flex; margin-right:10px;'],*/
                //'widget' => 'choice', //'single_text',
                'html5' => true,
            ])
            ->add('doctor_id', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'Doctor',
            ])
            ->add('doctor', EntityType::class, [
                'attr' => ['class' => 'form-group'],
                'required' => true,
                'class' => Doctor::class,
                'placeholder' => '-- Elige doctor --',
                'label' => 'Doctor',
                'choice_label' => function($doctor){
                    return $doctor->getFirstname() . ' ' . $doctor->getLastname();
                },
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'input-group'],
                'label' => 'Dirección',
            ])

            ->add('foto', FileType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Foto',
                'mapped' => false,
            ])
            
            /*->add('created_at', DateTimeType::class, [
                'disable' => true
            ])
            ->add('modified_at', DateTimeType::class, [
                'disabled' => true
            ])*/

            ->add('submit', SubmitType::class, [
                'label' => 'Añadir',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Paciente::class,
        ]);
    }
}
