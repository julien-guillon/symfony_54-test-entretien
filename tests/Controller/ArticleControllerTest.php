<?php

namespace App\Test\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ArticleRepository $repository;
    private EntityManagerInterface $entityManager;

    private string $articlePath = '/article';
    private string $articlesPath = '/articles';
    private string $articleTitle = 'Testing Web';
    private string $articleSlug = 'testing-web';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get(ArticleRepository::class);
        $this->entityManager = static::$container->get('doctrine')->getManager();
    }

    /* Compte la liste des articles sur le web
     * Et la compare avec la base de donnée
     */
    public function testWebArticles(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $crawler = $this->client->request('GET', $this->articlesPath);
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Articles');
        $count = $crawler->filter('.list-group-item')->count();
        self::assertEquals($originalNumObjectsInRepository, $count);
    }

    /* Ajout, modification et suppression de l'article sur le web */
    public function testWebAddShowAndDeleteArticle(): void
    {
        $articleSlug = sprintf('%s/%s', $this->articlePath, $this->articleSlug);
        /* Ajout de l'article */
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->request('GET', sprintf('%s/add', $this->articlesPath));
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Enregistrer', [
            'article[title]' => $this->articleTitle,
            'article[introduction]' => $this->articleTitle,
            'article[content]' => $this->articleTitle
        ]);
        self::assertResponseRedirects($articleSlug);
        self::assertEquals(($originalNumObjectsInRepository + 1), count($this->repository->findAll()));
        /* Fin ajout de l'article */

        /* 1ère visualisation de l'article */
        $crawler = $this->client->request('GET', $articleSlug);
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains($this->articleTitle);
        $lead = $crawler->filter('.lead');
        self::assertEquals(1, $lead->count());
        self::assertStringContainsString($this->articleTitle, $lead->first()->text());
        /* Fin 1ère visualisation de l'article */

        /* Edition de l'article */
        $this->client->request('GET', sprintf('%s/update/%s', $this->articlePath, $this->articleSlug));
        $this->client->submitForm('Enregistrer', [
            'article[introduction]' => 'Introduction edited',
            'article[content]' => 'Content edited'
        ]);
        self::assertResponseRedirects($articleSlug);
        /* Fin edition de l'article */

        /* 2nd visualisation de l'article */
        $crawler = $this->client->request('GET', $articleSlug);
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains($this->articleTitle);
        $lead = $crawler->filter('.lead');
        self::assertEquals(1, $lead->count());
        self::assertStringContainsString('Introduction edited', $lead->first()->text());
        self::assertStringContainsString('Content edited', $lead->first()->text());
        /* Fin 2nd visualisation de l'article */

        /* Suppression de l'article */
        $this->client->request('GET', sprintf('%s/update/%s', $this->articlePath, $this->articleSlug));
        $this->client->submitForm('Supprimer');
        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects(sprintf('%s', $this->articlesPath));
        /* Suppression de l'article */
    }
}
