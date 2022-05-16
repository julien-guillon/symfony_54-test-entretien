<?php

namespace App\Form;

use App\Entity\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre de l'article"
            ])
            ->add('introduction', TextType::class, [
                "label" => "Introduction (optionnel)",
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu textuel"
            ])
            ->add('photo', FileType::class, [
                'label' => "Image de l'article (optionnel)",
                'attr' => ['class' => 'form__inputFile'],
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4028k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Utilisez s\'il vous plait un fichier png, jpg ou webp',
                    ])
                ],
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
