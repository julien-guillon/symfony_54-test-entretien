<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    public function __construct(
                                private ManagerRegistry $doctrine,
                                private EntityManagerInterface $entityManager,
                                private ArticleRepository $repository
    ) {}

    #[Route('/articles', name: 'articles', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $this->repository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/articles/add', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/{slug}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $others =  $this->repository->findOtherArticles($article->getId());
        shuffle($others);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'others'  => array_slice($others, 0, 5)
        ]);
    }

    #[Route('/article/update/{slug}', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Article $article, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'edit' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();

            return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/article/delete/{slug}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token')))
        {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('articles', [], Response::HTTP_SEE_OTHER);
    }
}
