<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if(!$options['edit']) {
            $builder
                ->add('title', TextType::class, [
                      'required' => true,
                      'label' => 'Titre'
                ]);
        }
        $builder
            ->add('introduction', TextType::class, [
                  'required' => false,
                  'label' => 'Introduction'
              ])
            ->add('content', TextareaType::class, [
                  'label' => 'Contenu',
                  'attr' => [
                    'rows' => 10
                  ]
              ]);
        if(!$options['edit']) {
            $builder
                ->add('photoFile', VichImageType::class, [
                      'required' => false,
                      'label' => 'Photo'
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'edit' => false
        ]);
    }
}
