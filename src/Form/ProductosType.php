<?php

namespace App\Form;

use App\Entity\categorias;
use App\Entity\Productos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Type;


class ProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('peso')
            ->add('stock')
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
