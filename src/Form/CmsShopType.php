<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Form;

use App\Entity\CmsShop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                'attr' => ['class' => 'w-100 form-control']
            ])
            ->add('price', NumberType::class, [
                'attr' => ['class' => 'w-100 form-control']
            ])
            ->add('forceddescription', TextareaType::class, [
                'attr' => ['aria-hidden' => false],
                'required' => false
            ])
            ->add('quantity', NumberType::class, [
                'attr' => ['class' => 'w-100 form-control']
            ])
            ->add('promotion', NumberType::class, [
                'attr' => ['class' => 'w-100 form-control']
            ])
            ->add('visible', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'required' => false
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsShop::class,
        ]);
    }
}
