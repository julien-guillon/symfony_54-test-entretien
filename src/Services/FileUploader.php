<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\UrlHelper;

class FileUploader
{
    private string $uploadPath;
    private SluggerInterface $slugger;
    private UrlHelper $urlHelper;
    private string $relativeUploadsDir;

    public function __construct(string $publicPath, string $uploadPath, SluggerInterface $slugger, UrlHelper $urlHelper)
    {
        $this->uploadPath = $uploadPath;
        $this->slugger = $slugger;
        $this->urlHelper = $urlHelper;

        $this->relativeUploadsDir = str_replace($publicPath, '', $this->uploadPath).'/';
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getuploadPath(), $fileName);
        } catch (FileException $e) {
            // log de l'erreur TODO logger dans un gestionnaire de logs
            dump($e);
        }

        return $fileName;
    }

    public function getuploadPath(): string
    {
        return $this->uploadPath;
    }

    public function getUrl(?string $fileName, bool $absolute = true): ?string
    {
        if (empty($fileName)){
            return null;
        }

        if ($absolute) {
            return $this->urlHelper->getAbsoluteUrl($this->relativeUploadsDir.$fileName);
        }

        return $this->urlHelper->getRelativePath($this->relativeUploadsDir.$fileName);
    }
}
