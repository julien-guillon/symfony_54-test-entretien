<?php


namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleEditType
 * @package App\Form
 * Class qui étend ArticleAddType pour faciliter les évolutions
 */
class ArticleEditType extends ArticleAddType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        parent::buildForm($builder, $options);

        $builder
            ->remove('title')
            ->remove('slug')
            ->remove('photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }

}
