<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        // Création de 10 Articles

        for ($i = 1; $i < 10; $i++) {
            $article = new Article;
            $article
                ->setTitle($faker->sentence(3))
                ->setSlug($faker->slug(3))
                ->setIntroduction($faker->text(100))
                ->setContent($faker->paragraph(30));
            $manager->persist($article);
        }

        $manager->flush();
    }
}
