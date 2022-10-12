<?php


namespace App\Services;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class ImageOptimizer
 * @package App\Services
 * Classe utilitaire de gestion des images
 */
class ImageOptimizer
{

    private Imagine $imagine;
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->imagine = new Imagine();
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param string $filename
     * Méthode de gestion du redimensionnement des images
     */
    public function resize(string $filename): void
    {
        list($iwidth, $iheight) = getimagesize($filename);
        $ratio = $iwidth / $iheight;
        $width = $this->parameterBag->get('img_max_widht');
        $height = $this->parameterBag->get('img_max_height');
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($filename);
    }
}
