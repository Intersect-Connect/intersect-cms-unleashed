<?php

/**
 * Intersect CMS Unleashed
 * 2.2 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Form;

use App\Entity\CmsNews;
use App\Entity\CmsNewsCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsNewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control w-100']
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['aria-hidden' => false],
                'required' => false
            ])
            ->add('imgUrl', FileType::class, [
                "data_class" => null,
                'required' => false,
                 'mapped' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => CmsNewsCategory::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsNews::class,
        ]);
    }
}
