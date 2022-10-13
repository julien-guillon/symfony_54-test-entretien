<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Services\FileUploader;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class CreateArticleAction
 * @package App\Controller
 * Controller custom de gestion du POST Article REST
 */
#[AsController]
final class CreateArticleAction extends AbstractController
{


    private const ALLOWED_EXTENSION = ['image/png', 'image/jpeg'];

    private Serializer $serializer;
    private FileUploader $fileUploader;
    private ArticleRepository $articleRepository;

    public function __construct(FileUploader $fileUploader, ArticleRepository $articleRepository) {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->fileUploader = $fileUploader;
        $this->articleRepository = $articleRepository;
    }

    public function __invoke(Request $request, FileUploader $fileUploader): Article
    {

        $article = $this->serializer->deserialize($request->getContent(), Article::class, 'json');
        $uploadedFile = null;

        // vérification du respect des contraintes de format
        if($article->getTitle() === null || $article->getSlug() === null || $article->getContent() === null) {
            throw new BadRequestHttpException('Merci de vérifier que les champs requis sont bien renseignés');
        }

        if(strlen($article->getTitle()) > 150 || strlen($article->getSlug()) > 150 || strlen($article->getIntroduction()) > 255) {
            throw new BadRequestHttpException('Merci de réduire la longueur des contributions');
        }


        // gestion du fichier
        if($article->getFile() !== null) {
            $uploadedFile = new UploadedBase64EncodedFile(new Base64EncodedFile($article->getFile()));
        }

        if($uploadedFile !== null && !in_array(mime_content_type($uploadedFile->getPath() . '/' . $uploadedFile->getFilename()), self::ALLOWED_EXTENSION) ) {
            throw new BadRequestHttpException('Merci d\ajouter un fichier au format JPEG ou PNG');
        }

        if($uploadedFile !== null) {
            $article->setPhoto($this->fileUploader->upload($uploadedFile));
        }

        $article = $this->articleRepository->handleSlug($article);

        // on supprime le fichier avant de retourner la réponse
        $article->setFile(null);

        return $article;
    }
}
