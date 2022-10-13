<?php

namespace App\Repository;

use App\Entity\Article;
use App\Services\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{

    private FileUploader $fileUploader;

    public function __construct(
        ManagerRegistry $registry,
        FileUploader $fileUploader
    )
    {
        parent::__construct($registry, Article::class);
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param Article $article
     * Gestion de l'ajout d'un nouvel article
     */
    public function addArticle(Article $article, FormInterface $form): void
    {

        $article = $this->handleSlug($article);

        // gestion de la photo
        if($article->getPhoto() !== null) {
            // récupération du fichier
            $file = $form->get('photo')->getData();

            // gestion du stockage
            $fileName = $this->fileUploader->upload($file);

            $article->setPhoto($fileName);
        }

        // enregistrement en BDD
        $this->saveArticleInDataBase($article);

    }

    /**
     * @param Article $article
     * MAJ d'un article en BDD
     */
    public function editArticle(Article $article): void
    {
        $this->saveArticleInDataBase($article);
    }

    /**
     * @param Article $article
     * Suppression d'un article en BDD
     */
    public function removeArticle(Article $article): void
    {
        $filesystem = new Filesystem();

        // si une photo est associée à l'article, on la supprime
        if($article->getPhoto() !== null) {
            try {
                $filesystem->remove($this->parameterBag->get('photos_directory'). '/' . $article->getPhoto());
            } catch (IOExceptionInterface $e) {
                // log de l'erreur TODO logger dans un gestionnaire de logs
                dump($e);
            }
        }


        $this->removeArticleInDataBase($article);
    }

    /**
     * @param string $field
     * @param string $value
     * @param array|null $orderBy
     * @param int|null $limit
     * @return array|null
     * Méthode retournant les articles ne correspondants pas aux critères passés en paramètres (WHERE NOT)
     */
    public function findArticleByNot(string $field, string $value, array $orderBy = null, int $limit = null): ?array
    {
        $qb = $this->createQueryBuilder('article');
        $qb->where($qb->expr()->not($qb->expr()->eq('article.'.$field, '?1')));
        $qb->setParameter(1, $value);

        if($orderBy) {
            foreach($orderBy as $field => $order) {
                $qb->addOrderBy( 'article.' . $field, $order );
            }
        }

        if($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param Article $article
     * Méthode de gestion du slug
     */
    public function handleSlug(Article $article): Article
    {
        $slug = strtolower(trim($article->getSlug()));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', "-", $slug);
        if($this->findOneBy(['slug' => $slug]) !== null) {
            $slug = $slug . uniqid();
        }
        return $article->setSlug($slug);
    }


    /**
     * @param Article $article
     * Méthode d'exécution de l'ajout / modification
     */
    private function saveArticleInDataBase(Article $article): void
    {
        try {
            $this->getEntityManager()->persist($article);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException | ORMException $e) {
            // log de l'erreur TODO logger dans un gestionnaire de logs
            dump($e);
        }
    }

    /**
     * @param Article $article
     * Méthode d'exécution de la suppression
     */
    private function removeArticleInDataBase(Article $article): void
    {
        try {
            $this->getEntityManager()->remove($article);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException | ORMException $e) {
            // log de l'erreur TODO logger dans un gestionnaire de logs
            dump($e);
        }
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
