<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
            'label' => 'Titre',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'invalid_message'=>'Déjà utilisé',
            ])
            ->add('introduction', TextType::class, [
                'label' => 'Introduction',
                'required' =>  false,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Corps de l\'article',
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Illustration',
                'required' =>  false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}