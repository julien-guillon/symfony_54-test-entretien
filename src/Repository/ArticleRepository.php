<?php

namespace App\Repository;

use App\Entity\Article;
use App\Services\ImageOptimizer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{

    private ImageOptimizer $imageOptimizer;
    private ParameterBagInterface $parameterBag;
    private SluggerInterface $slugger;

    public function __construct(
        ManagerRegistry $registry,
        ImageOptimizer $imageOptimizer,
        ParameterBagInterface $parameterBag,
        SluggerInterface $slugger
    )
    {
        parent::__construct($registry, Article::class);
        $this->imageOptimizer = $imageOptimizer;
        $this->parameterBag = $parameterBag;
        $this->slugger = $slugger;
    }

    /**
     * @param Article $article
     * Gestion de l'ajout d'un nouvel article
     */
    public function addArticle(Article $article, FormInterface $form)
    {

        // gestion de la photo
        if($article->getPhoto() !== null) {
            // récupération du fichier
            $file = $form->get('photo')->getData();

            // gestion du renommage
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName =  $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // gestion du stockage
            $photos_directory = $this->parameterBag->get('photos_directory');
            try {
                $file->move(
                    $photos_directory,
                    $fileName
                );
                $this->imageOptimizer->resize($photos_directory . '/' .$fileName); // resize image
            } catch (FileException $e) {
                // log de l'erreur TODO logger dans un gestionnaire de logs
                dump($e);
            }

            $article->setPhoto($fileName);
        }

        // enregistrement en BDD
        $this->saveArticleInDataBase($article);

    }

    /**
     * @param Article $article
     * MAJ d'un article en BDD
     */
    public function editArticle(Article $article) {

        $this->saveArticleInDataBase($article);
    }

    /**
     * @param Article $article
     * Suppression d'un article en BDD
     */
    public function removeArticle(Article $article) {
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
     * Méthode d'exécution de l'ajout / modification
     */
    private function saveArticleInDataBase(Article $article)
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
    private function removeArticleInDataBase(Article $article) {
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
