<?php

namespace App\Form;

use App\Entity\CmsShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idItem', TextType::class, [
                'attr' => ['class' => 'w-100']
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'w-100']
            ])
            ->add('forceddescription', TextareaType::class, [
                'attr' => ['aria-hidden' => false],
                'required' => false
            ])
            ->add('quantity', NumberType::class, [
                'attr' => ['class' => 'w-100']
            ])
            ->add('promotion', NumberType::class, [
                'attr' => ['class' => 'w-100']
            ])
            ->add('visible', CheckboxType::class, [

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsShop::class,
        ]);
    }
}
