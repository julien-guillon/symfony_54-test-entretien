<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if(!$options['edit']) {
            $builder
                ->add('title', TextType::class, ['required' => true]);
        }
        $builder
            ->add('introduction', TextType::class, ['required' => false])
            ->add('content', TextareaType::class);
        if(!isset($options['edit'])) {
            $builder
                ->add('photo', FileType::class, [
                      'required' => false,
                      'constraints' => [
                            new Assert\File([
                                'maxSize' => '4mi',
                                'mimeTypes' => [
                                             'image/png',
                                             'image/jpeg'
                                ],
                                'mimeTypesMessage' => 'Veuillez seulement utiliser une image jpeg ou png.',
                            ])
                      ]
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
