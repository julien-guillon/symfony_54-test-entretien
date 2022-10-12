<?php

namespace App\Controller;

use App\Entity\Article;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateArticleAction extends AbstractController
{

    private const ALLOWED_EXTENSION = ['image/png', 'image/jpeg'];

    public function __invoke(Request $request, FileUploader $fileUploader): Article
    {
        $title = $request->get('title');
        $slug = $request->get('slug');
        $content = $request->get('content');
        $introduction = $request->get('introduction');
        $uploadedFile = $request->files->get('file');

        if($title === null || $slug === null || $content === null) {
            throw new BadRequestHttpException('Merci de vérifier que les champs requis sont bien renseignés');
        }

        if($uploadedFile !== null && !in_array(mime_content_type($uploadedFile), self::ALLOWED_EXTENSION) ) {
            throw new BadRequestHttpException('Merci d\ajouter un fichier au format JPEG ou PNG');
        }

        $article = new Article();
        $article->setTitle($title);
        $article->setSlug($slug);
        $article->setContent($content);
        $article->setIntroduction($request->get($introduction));

        if($uploadedFile !== null) {
            $article->setPhoto($fileUploader->upload($uploadedFile));
        }

        return $article;
    }
}
