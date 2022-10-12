<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $title = substr(file_get_contents('https://loripsum.net/api/1/plaintext/short'),0, 100);
        $introduction = substr(file_get_contents('https://loripsum.net/api/1/plaintext/short'),0, 149);
        $content = substr(file_get_contents('https://loripsum.net/api/1/plaintext/medium'),0, 250);

        for ($i = 0; $i < 20; $i++) {
            $article = new Article();
            $article->setTitle($title . $i);
            $article->setIntroduction($introduction);
            $article->setContent($content);
            $article->setSlug('lorem'. $i);
            $article->setPhoto( 'default.png');
            $manager->persist($article);
        }


        $manager->flush();
    }

}
