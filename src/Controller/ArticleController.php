<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

class ArticleController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {}

    /**
     *
     * @Route("/articles", name="liste_articles")
     */
    public function index(): Response
    {
        $articles = $this->doctrine->getRepository(Article::class)->findAll();

        return $this->render('articles/articles.html.twig', ['articles' => $articles]);
    }

    /**
     *
     * @Route("/article/{id}", name="show_article")
     */
    public function show(Article $article): Response
    {
        $articles_connexes = $this->doctrine->getRepository(Article::class)->findBy(
            array(),
            array(),
            3,
            0
        );

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'articles_connexes' => $articles_connexes
        ]);
    }

    /**
     *
     * @Route("/articles/add", name="add_article")
     */
    public function add(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($article->getPhoto() !== null) {
                $file = $form->get('photo')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_folder'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $article->setPhoto($fileName);
            }

            $em = $this->doctrine->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article créé avec succès !');
            return $this->redirectToRoute('liste_articles');
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/article/update/{id}", name="update_article")
     */
    public function update(Article $article, Request $request): Response
    {
        $photo = $article->getPhoto();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($article->getPhoto() !== null) {
                $article->setPhoto($photo);
            }

            $em = $this->doctrine->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article modifié avec succès !');
            return $this->redirectToRoute('liste_articles');
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/article/delete/{id}", name="delete_article")
     */
    public function delete(Article $article, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token')))
        {
            $imageFileName = $article->getPhoto();
            if ($article->getPhoto() !== null) {
                $fileSystem = new Filesystem();
                $image_directory = $this->getParameter('images_folder');
                $fileSystem->remove($image_directory.'/'.$imageFileName);
            }

            $em = $this->doctrine->getManager();
            $em->remove($article);
            $em->flush();
            $this->addFlash('success', 'L\'article a bien été supprimé.');
        }
        return $this->redirectToRoute('liste_articles');
    }

}
