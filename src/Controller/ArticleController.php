<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\EditArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {

        $articles = $this->em->getRepository(Article::class)->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }

        /**
     * @Route("/articles", name="articles")
     */
    public function index(): Response
    {
        $articles = $this->em->getRepository(Article::class)->findAll();

        return $this->render('articles/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{slug}", name="show_article")
     */
    public function show(Article $article, Request $request): Response
    {
        $article = $this->em->getRepository(Article::class)->findOneBy([
            'slug' => $article->getSlug(),
        ]);

        $last_articles = $this->em->getRepository(Article::class)->findBy([],['id' => 'DESC'], 3);

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'last_articles' => $last_articles,
        ]);
    }

    /**
     * @Route("/articles/add", name="add_article")
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('articles');
        }

        return $this->render("articles/new.html.twig", [
            "formArticle" => $form->createView(),
        ]);
    }

    /**
     * @Route("/articles/edit/{slug}", name="edit_article")
     */
    public function edit(Article $article, Request $request): Response
    {
        $article = $this->em->getRepository(Article::class)->findOneBy([
            'slug' => $article->getSlug(),
        ]);

        $form = $this->createForm(EditArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('articles');
        }

        return $this->render("articles/edit.html.twig", [
            'article' => $article,
            "formArticle" => $form->createView(),
        ]);
    }

    /**
     * @Route("/articles/delete/{slug}", name="delete_article")
     */

    public function delete(Article $article): Response
    {
        $this->em->remove($article);
        $this->em->flush();

        return $this->redirectToRoute('articles');
    }


}