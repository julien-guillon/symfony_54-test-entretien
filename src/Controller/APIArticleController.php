<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class APIArticleController extends AbstractController
{
    public function __construct(
                                private ManagerRegistry $doctrine,
                                private ArticleRepository $repository,
                                private EntityManagerInterface $entityManager,
    ) { }

    #[Route('/api/articles', name: 'api_article_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json([
            'articles' => $this->repository->findAll(),
        ]);
    }

    #[Route('/api/articles/add', name: 'api_article_new', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $article = new Article();
        $article->setTitle((string) $request->request->get('title'));
        $introduction = $request->request->get('introduction');
        if(!empty($introduction)) {
            $article->setIntroduction($introduction);
        }
        $article->setContent((string) $request->request->get('content'));

        // Crée un slug à partir du titre et s'assure qu'il est unique,
        // Sinon ajout, à la fin du slug, d'un identifiant unique basé sur la date et l'heure
        $slugger = new AsciiSlugger();
        $testSlug = $slugger->slug($article->getTitle());
        $testArticle = $this->repository->findOneBy(['slug' => $testSlug]);
        if(!empty($testArticle)) {
            $testSlug .= '-'.uniqid();
        }
        $article->setSlug($testSlug);

        $errors = $validator->validate($article);

        if (count($errors) > 0)
        {
            return $this->json(['error' => $errors->get(0)->getPropertyPath().': '.$errors->get(0)->getMessage()], 400);
        }

        // Vérifie la photo
        $photo = $request->files->get('photo');
        if ($photo)
        {
            $validator = Validation::createValidator();
            $errors = $validator->validate($photo, [
                                               new Assert\File([
                                                    'maxSize' => '4mi',
                                                    'mimeTypes' => [
                                                                 'image/png',
                                                                 'image/jpeg'
                                                    ],
                                                    'mimeTypesMessage' => 'Veuillez seulement utiliser une image jpeg ou png.',
                                                ])
               ]
            );
            if (count($errors) > 0)
            {
                return $this->json(['error' => $errors->get(0)->getPropertyPath().': '.$errors->get(0)->getMessage()], 400);
            }
            $newFilename = uniqid().'.'.$photo->guessExtension();
            try {
                $photo->move(
                    $this->getParameter('photos_dir'),
                    $newFilename
                );
            } catch (FileException $e) {
                return new Response($e->getMessage());
            }
            $article->setPhoto($newFilename);
        }

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->json(['success' => 1, 'article' => $article]);
    }

    #[Route('/api/article/{slug}', name: 'api_article_show', methods: ['GET'])]
    public function show(string $slug): JsonResponse
    {
        $article = $this->doctrine->getRepository(Article::class)->findOneBy(['slug' => $slug]);
        if(empty($article)) {
            return $this->json(['error' => 'Non trouvé.'], 404);
        }
        $others = $this->doctrine->getRepository(Article::class)->findOtherArticles($article->getId());
        return $this->json([
            'article' => $article,
            'others'  => $others
        ]);
    }

    #[Route('/api/article/update/{slug}', name: 'api_article_edit', methods: ['POST'])]
    public function edit(Request $request, string $slug, ValidatorInterface $validator): JsonResponse
    {
        $article = $this->doctrine->getRepository(Article::class)->findOneBy(['slug' => $slug]);
        if(empty($article)) {
            return $this->json(['error' => 'Non trouvé.'], 404);
        }
        $article->setIntroduction((string) $request->request->get('introduction'));
        $article->setContent((string) $request->request->get('content'));

        $errors = $validator->validate($article);

        if (count($errors) > 0)
        {
            return $this->json(['error' => $errors->get(0)->getPropertyPath().': '.$errors->get(0)->getMessage()], 400);
        }

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->json(['success' => 1, 'article' => $article]);
    }

    #[Route('/api/article/delete/{slug}', name: 'api_article_delete', methods: ['DELETE'])]
    public function delete(string $slug): JsonResponse
    {
        $article = $this->doctrine->getRepository(Article::class)->findOneBy(['slug' => $slug]);
        if(empty($article)) {
            return $this->json(['error' => 'Non trouvé.'], 404);
        }
        $photo = $article->getPhoto();
        if ($photo !== null) {
            $fileSystem = new Filesystem();
            $image_directory = $this->getParameter('photos_dir');
            $fileSystem->remove($image_directory.'/'.$photo);
        }

        $this->entityManager->remove($article);
        $this->entityManager->flush();
        return $this->json(['success' => 1]);
    }
}
