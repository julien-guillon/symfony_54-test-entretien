<?php
 
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api", name="api_")
 */
class ApiArticleController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("/article", name="api_article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
 
        $data = [];
 
        foreach ($articles as $article) {
           $data[] = [
               'id' => $article->getId(),
               'title' => $article->getTitle(),
               'slug' => $article->getSlug(),
               'introduction' => $article->getIntroduction(),
               'content' => $article->getContent(),
               'photo' => $article->getPhoto(),
           ];
        }
 
 
        return $this->json($data);
    }
 
    /**
     * @Route("/article/add", name="api_article_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();
 
        $article = new Article();
        $article->setTitle($request->request->get('title'));
        $article->setSlug($request->request->get('slug'));
        $article->setIntroduction($request->request->get('introduction'));
        $article->setContent($request->request->get('content'));
        $article->setPhoto($request->request->get('photo'));
 
        $entityManager->persist($article);
        $entityManager->flush();
 
        return $this->json('Created new article successfully with id ' . $article->getId());
    }
 
    /**
     * @Route("/article/{id}", name="api_article_show", methods={"GET"})
     */
    public function show(ArticleRepository $articleRepository, int $id): Response
    {
        $article = $articleRepository->find($id);
 
        if (!$article) {
 
            return $this->json('No article found for id' . $id, 404);
        }
 
        $data =  [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'slug' => $article->getSlug(),
            'introduction' => $article->getIntroduction(),
            'content' => $article->getContent(),
            'photo' => $article->getPhoto(),
        ];
         
        return $this->json($data);
    }
 
    /**
     * @Route("/article/update/{id}", name="api_article_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);
 
        if (!$article) {
            return $this->json('No article found for id' . $id, 404);
        }
 
        $article->setIntroduction($request->request->get('introduction'));
        $article->setContent($request->request->get('content'));
        $entityManager->flush();
 
        $data =  [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'slug' => $article->getSlug(),
            'introduction' => $article->getIntroduction(),
            'content' => $article->getContent(),
            'photo' => $article->getPhoto(),
        ];
         
        return $this->json($data);

    }
 
    /**
     * @Route("/article/delete/{id}", name="api_article_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);
 
        if (!$article) {
            return $this->json('No article found for id' . $id, 404);
        }
 
        $entityManager->remove($article);
        $entityManager->flush();
 
        return $this->json('Deleted a article successfully with id ' . $id);
    }
 
 
}