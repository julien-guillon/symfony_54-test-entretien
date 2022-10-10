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

    public function __construct(ManagerRegistry $registry, ImageOptimizer $imageOptimizer, ParameterBagInterface $parameterBag)
    {
        parent::__construct($registry, Article::class);
        $this->imageOptimizer = $imageOptimizer;
        $this->parameterBag = $parameterBag;
    }


    /**
     * @param Article $article
     * Ajout d'un article en BDD
     */
    public function addArticle(Article $article, FormInterface $form)
    {

        // gestion de la photo
        if($article->getPhoto() !== null) {
            $file = $form->get('photo')->getData();
            $fileName =  uniqid(). '.' .$file->guessExtension();
            $photos_directory = $this->parameterBag->get('photos_directory');
            try {
                $file->move(
                    $photos_directory,
                    $fileName
                );
                $this->imageOptimizer->resize($photos_directory . '/' .$fileName); // resize image
            } catch (FileException $e) {
                // log de l'erreur
                dump($e);
            }

            $article->setPhoto($fileName);
        }

        // enregistrement en BDD
        try {
            $this->getEntityManager()->persist($article);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException | ORMException $e) {
            // log de l'erreur
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
