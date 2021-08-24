<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Form;

use App\Entity\CmsPages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsPagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => ['Game' => 'game', 'Wiki' => "wiki", "Legal" => "Legal"],
                'attr' => ['class' => 'w-100 form-control']
            ])
            ->add('name', TextType::class, [
                'attr' => ['class' => 'w-100 form-control']
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['aria-hidden' => false],
                'required' => false
            ])
            ->add('is_visible', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input'],
                'required' => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsPages::class,
        ]);
    }
}
