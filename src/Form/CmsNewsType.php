<?php

namespace App\Form;

use App\Entity\CmsNews;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsNews::class,
        ]);
    }
}
