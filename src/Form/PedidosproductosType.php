<?php

namespace App\Form;

use App\Entity\Pedidos;
use App\Entity\Pedidosproductos;
use App\Entity\productos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidosproductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('unidades')
            ->add('pedido', EntityType::class, [
                'class' => Pedidos::class,
'choice_label' => 'id',
            ])
            ->add('producto', EntityType::class, [
                'class' => productos::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pedidosproductos::class,
        ]);
    }
}
