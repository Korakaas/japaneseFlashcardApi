<?php

namespace App\Repository;

use App\Entity\FlashcardVocabulary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashcardVocabulary>
 *
 * @method FlashcardVocabulary|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashcardVocabulary|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashcardVocabulary[]    findAll()
 * @method FlashcardVocabulary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashcardVocabularyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashcardVocabulary::class);
    }

//    /**
//     * @return FlashcardVocabulary[] Returns an array of FlashcardVocabulary objects
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

//    public function findOneBySomeField($value): ?FlashcardVocabulary
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
