<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
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
    public function findByFilters(?int $categoryId, ?int $authorId): array
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.Category', 'c') // <- attention à la casse
            ->leftJoin('a.author', 'u')
            ->addSelect('c', 'u')
            ->orderBy('a.id', 'DESC');

        if ($categoryId) {
            $qb->andWhere('c.id = :category')
            ->setParameter('category', $categoryId);
        }

        if ($authorId) {
            $qb->andWhere('u.id = :author')
            ->setParameter('author', $authorId);
        }

        return $qb->getQuery()->getResult();
    }
}
