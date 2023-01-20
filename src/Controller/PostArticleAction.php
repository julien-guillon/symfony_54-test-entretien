<?php

namespace App\Controller;


use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

class PostArticleAction
{
    public function __invoke(Request $request): Article
    {
        $data = $request->request;

        $mediaObject = new Article();

        $mediaObject->setTitle($data->get('title'));
        if ($data->get('introduction')) $mediaObject->setIntroduction($data->get('introduction'));
        $mediaObject->setContent($data->get('content'));

        $file = $request->files->get('photo');
        if ($file){
            $mediaObject->setPhotoFile($file);
        }

        return $mediaObject;
    }

}
