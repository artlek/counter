<?php

namespace App\Form;

use App\Entity\Counter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AddCounterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'e.g. electricity meter']
            ])
            ->add('startPoint', NumberType::class, [
                'attr' => ['placeholder' =>'current counter usage']
            ])
            ->add('price', NumberType::class, [
                'attr' => ['placeholder' => 'price for one unit']
            ])
            ->add('unit', TextType::class, [
                'attr' => ['placeholder' =>'e.g. kWh']
            ])
            ->add('currency')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Counter::class,
        ]);
    }
}
