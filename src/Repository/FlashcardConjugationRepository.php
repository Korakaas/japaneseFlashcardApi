<?php

namespace App\Repository;

use App\Entity\FlashcardConjugation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashcardConjugation>
 *
 * @method FlashcardConjugation|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashcardConjugation|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashcardConjugation[]    findAll()
 * @method FlashcardConjugation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashcardConjugationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashcardConjugation::class);
    }

//    /**
//     * @return FlashcardConjugation[] Returns an array of FlashcardConjugation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FlashcardConjugation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
