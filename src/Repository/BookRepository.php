<?php

namespace App\Repository;

use App\Entity\Book;
use App\Search\BookSearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function search(BookSearchCriteria $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('b');

        if ($criteria->q) {
            $queryBuilder->andWhere('b.title LIKE :q')
                ->setParameter('q', '%' . $criteria->q . '%');
        }

        if ($criteria->author) {
            $queryBuilder->innerJoin('b.authors', 'a')
                ->andWhere('a.name LIKE :author')
                ->setParameter('author', '%' . $criteria->author . '%');
        }

        if ($criteria->available) {
            $queryBuilder->andWhere('b.available = :available')
                ->setParameter('available', $criteria->available);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
