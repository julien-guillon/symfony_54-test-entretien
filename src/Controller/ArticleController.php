<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AddArticleType;
use App\Form\UpdateArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;

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
     * @Route("/articles", name="articles")
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $articles = $this->em->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/add", name="article_add")
     */
    public function add(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();

            return $this->redirectToRoute('articles');
        }

        return $this->render("article/add.html.twig", [
            "form" => $form->createView(),
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

        $aside_articles = $this->em->getRepository(Article::class)->findBy([],['id' => 'DESC'], 3);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'aside_articles' => $aside_articles,
        ]);
    }

    /**
     * @Route("/article/update/{slug}", name="update_article")
     */
    public function update(Article $article, Request $request): Response
    {
        $updateArticleForm = $this->createForm(UpdateArticleType::class, $article);
        $updateArticleForm->handleRequest($request);

        if ($updateArticleForm->isSubmitted() && $updateArticleForm->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('show_article',[
                'slug' => $article->getSlug(),
            ]);
        }

        return $this->render('article/update.html.twig', [
            'form' => $updateArticleForm->createView()
        ]);
    }

    /**
     * @Route("/article/delete/{slug}", name="delete_article")
     */
    public function delete(Article $article): Response
    {
        $this->em->remove($article);
        $this->em->flush();

        return $this->redirectToRoute('articles');
    }

//    /**
//     * @param CropperInterface $cropper
//     * @param Request $request
//     * @return void
//     */
//    public function cropper(CropperInterface $cropper, Request $request)
//    {
//        $imagePath = $this->getParameter('kernel.project_dir').'/public/uploads/some_file.png';
//        $crop = $cropper->createCrop($imagePath);
//
//        $form = $this->createFormBuilder(['crop' => $crop])
//            ->add('crop', CropperType::class, [
//                'public_url' => '/uploads/some_file.png',
//            ])->getForm();
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $cropped = $crop->getCroppedImage();
//            $croppedThumbnail = $crop->getCroppedThumbnail(200, 150);
//        }
//    }
}
