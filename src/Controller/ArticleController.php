<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleAddType;
use App\Form\ArticleEditType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 * Controller de gestion des articles
 */
class ArticleController extends AbstractController
{

    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return Response
     * Route de listing des articles
     */
    #[Route('/articles', name: 'articles')]
    public function index(): Response
    {
        // order by id pour retourner les derniers articles ajoutés en 1er
        $articles = $this->articleRepository->findBy([], ['id' => 'DESC']);
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * Route d'ajout d'un article
     */
    #[Route('/articles/add', name: 'add_article')]
    public function add(Request $request): Response
    {

        $article = new Article();
        $form = $this->createForm(ArticleAddType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->articleRepository->addArticle($article, $form);
            return $this->redirectToRoute('show_article', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param string $slug
     * @return Response
     * Méthode d'affichage d'un article
     */
    #[Route('/article/{slug}', name: 'show_article')]
    public function show(string $slug): Response
    {
        $article = $this->articleRepository->findOneBy(['slug' => $slug]);

        if($article === null) {
            throw new NotFoundHttpException('Cet article n\'existe pas');
        }

        $articlesSeeMore = $this->articleRepository->findArticleByNot('id', $article->getId(), ['id' => 'DESC'], 3);



        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articlesSeeMore' => $articlesSeeMore
        ]);
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return Response
     * Méthode d'édition d'un article
     */
    #[Route('/article/update/{slug}', name: 'edit_article')]
    public function edit(Article $article, Request $request): Response
    {
        $form = $this->createForm(ArticleEditType::class, $article, ['required' => false]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->articleRepository->editArticle($article);
            return $this->redirectToRoute('show_article', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @param Article $article
     * @return Response
     * Méthode de suppression d'un article
     */
    #[Route('/article/delete/{slug}', name: 'delete_article')]
    public function delete(Article $article): Response
    {
        $this->articleRepository->removeArticle($article);

        return $this->redirectToRoute('articles');
    }

}
