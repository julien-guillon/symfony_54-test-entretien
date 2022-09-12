<?php

namespace App\Tests;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class APIArticlesTest extends ApiTestCase
{
    private ArticleRepository $repository;
    private EntityManagerInterface $entityManager;

    private string $apiPath = '/api/articles';
    private string $articleTitle = 'Testing API';
    private string $articleSlug = 'testing-api';

    protected function setUp(): void
    {
        $this->repository = static::getContainer()->get(ArticleRepository::class);
        $this->entityManager = static::$container->get('doctrine')->getManager();
    }

    /* Compte la liste des articles sur l'API
     * Et la compare avec la base de donnée
     */
    public function testAPIArticles(): void
    {
        $response = static::createClient()->request('GET', $this->apiPath);
        $originalNumObjectsInRepository = count($this->repository->findAll());
        self::assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertEquals($originalNumObjectsInRepository, $content['hydra:totalItems']);
    }

    /* Ajout, modification et suppression de l'article sur l'API */
    public function testAPIAddShowAndDeleteArticle(): void
    {
        $articleSlug = sprintf('%s/%s', $this->apiPath, $this->articleSlug);
        /* Ajout de l'article */
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $response = static::createClient()->request('POST', $this->apiPath, [
           'json' => [
                'title' => $this->articleTitle,
                'introduction' => $this->articleTitle,
                'content' => $this->articleTitle
            ]
        ]);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(($originalNumObjectsInRepository + 1), count($this->repository->findAll()));
        /* Fin ajout de l'article */

        /* 1ère visualisation de l'article */
        $response = static::createClient()->request('GET', $articleSlug);
        self::assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertEquals($this->articleTitle, $content['title']);
        self::assertEquals($this->articleSlug, $content['slug']);
        self::assertEquals($this->articleTitle, $content['introduction']);
        self::assertEquals($this->articleTitle, $content['content']);
        /* Fin 1ère visualisation de l'article */

        /* Edition de l'article */
        $response = static::createClient()->request('PATCH', $articleSlug, [
            'headers' => [
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => [
                'introduction' => 'Introduction edited',
                'content' => 'Content edited'
            ]
        ]);
        self::assertEquals(200, $response->getStatusCode());
        /* Fin edition de l'article */

        /* 2nd visualisation de l'article */
        $response = static::createClient()->request('GET', $articleSlug);
        self::assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        self::assertEquals($this->articleTitle, $content['title']);
        self::assertEquals($this->articleSlug, $content['slug']);
        self::assertStringContainsString('Introduction edited', $content['introduction']);
        self::assertStringContainsString('Content edited', $content['content']);
        /* Fin 2nd visualisation de l'article */

        /* Suppression de l'article */
        $response = static::createClient()->request('DELETE', $articleSlug);
        self::assertEquals(204, $response->getStatusCode());
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        /* Suppression de l'article */
    }

}
