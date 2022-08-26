<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AddArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
            'label' => 'Titre de l\'article',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Chemin (slug)',
                'invalid_message'=>'Déjà utilisé',
//                'required' =>  false,
            ])
            ->add('introduction', TextType::class, [
                'label' => 'Introduction/Résumé',
                'required' =>  false,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenue',
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo',
                'required' =>  false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
