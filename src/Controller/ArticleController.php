<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\UpdateArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {

        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Method d'ajout du fichier dans le dossier uploads
            $uploadedFile = $form->get('photo')->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

            try {
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
            } catch (FileException $e) {
                error_log($e->getMessage());
            }
            $article->setPhoto($newFilename);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article, ArticleRepository $articleRepository): Response
    {

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'articles' => $articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/update/{id}/", name="article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(UpdateArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }

}
