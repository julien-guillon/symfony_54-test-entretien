<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Vich\UploaderBundle\FileAbstraction\ReplacingFile;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Paramètres de génération
        $totalArticles = 20; // Nombre total d'articles
        $minimumIntros = 40; // Nombre d'intros minimum en %
        $minimumPhotos = 70; // Nombre de photos minimum en %
        // Fin paramètres de génération

        $tempPhotosDir = __DIR__."/Images/";
        if(!is_dir($tempPhotosDir)) {
            mkdir($tempPhotosDir);
        } else {
            self::rrmdir($tempPhotosDir);
            mkdir($tempPhotosDir);
        }
        $faker = Factory::create('fr_FR');
        $totalIntros = 0;
        $totalPhotos = 0;
        
        for ($i = 0; $i < $totalArticles; $i++)
        {
            $article = new Article();

            $article->setTitle($faker->text(mt_rand(10, 150)));

            if (
                (mt_rand(1, 2) % 2) ||
                ((($minimumIntros / 100) - ($totalIntros / $totalArticles)) > (1 - (($i + 1) / $totalArticles)))
            ) {
                $totalIntros++;
                $article->setIntroduction($faker->text(mt_rand(10, 150)));
            }

            if (
                (mt_rand(1, 2) % 2) ||
                ((($minimumPhotos / 100) - ($totalPhotos / $totalArticles)) > (1 - (($i + 1) / $totalArticles)))
            ) {
                $photoContent = @file_get_contents('https://loremflickr.com/800/800?random='.$totalPhotos);
                if(!empty($photoContent))
                {
                    $newFilename = uniqid().'.jpg';
                    file_put_contents($tempPhotosDir.$newFilename, $photoContent);
                    $photo = new ReplacingFile($tempPhotosDir.$newFilename);
                    $article->setPhotoFile($photo);
                    $totalPhotos++;
                }
                unset($photoContent);
            }

            $article->setContent($faker->paragraphs(mt_rand(5, 25), true));

            $manager->persist($article);
        }

        $manager->flush();

        self::rrmdir($tempPhotosDir);

    }

    private static function rrmdir($dir) {
        if(is_dir($dir)) {
            self::cchmod($dir, 0777);
            $objects = @scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if(filetype($dir.'/'.$object) == 'dir') {
                        self::rrmdir($dir.'/'.$object);
                    } else {
                        @unlink($dir.'/'.$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        } else {
            @unlink($dir);
        }
    }

    private static function cchmod($dir, $value) {
        if(is_dir($dir)) {
            @chmod($dir, $value);
            $objects = @scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if(filetype($dir.'/'.$object) == 'dir') {
                        self::cchmod($dir.'/'.$object, $value);
                    } else {
                        @chmod($dir.'/'.$object, $value);
                    }
                }
            }
            reset($objects);
        } else {
            @chmod($dir, $value);
        }
    }
}
