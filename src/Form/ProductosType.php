<?php

namespace App\Form;

use App\Entity\categorias;
use App\Entity\Productos;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class ProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce un nombre.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZñÑ\s]+$/u',
                        'message' => 'El nombre solo debe contener letras.',
                    ]),
                ]
            ])
            ->add('descripcion', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce un descripcion.',
                    ]),
                ]
            ])            
            ->add('peso')
            ->add('stock', NumberType::class, [
                'constraints' => [
                    new Type([
                        'type' => 'numeric',
                        'message' => 'El stock debe ser un número.',
                    ]),
                    new NotBlank([
                        'message' => 'Por favor, introduce un valor para el stock.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'El stock debe ser un número entero positivo.',
                    ]),
                    
                    new \Symfony\Component\Validator\Constraints\GreaterThan([
                        'value' => 0,
                        'message' => 'El stock debe ser mínimo 1.',
                    ]),
                ],
            ])
            ->add('precio', NumberType::class, [
                'scale' => 2,'constraints' => [
                new Type([
                    'type' => 'numeric',
                    'message' => 'El precio debe ser un número.',
                ]),]
            ])
            ->add('categoria', EntityType::class, [
                'class' => categorias::class,
                'choice_label' => 'nombre',
            ])
            ->add('imagen', FileType::class, [
                'label' => 'Imagen',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, introduce una imagen.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Productos::class,
        ]);
    }
}
