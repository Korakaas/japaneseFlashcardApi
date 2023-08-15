<?php

namespace App\Repository;

use App\Entity\FlashcardKanji;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashcardKanji>
 *
 * @method FlashcardKanji|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashcardKanji|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashcardKanji[]    findAll()
 * @method FlashcardKanji[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashcardKanjiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashcardKanji::class);
    }

//    /**
//     * @return FlashcardKanji[] Returns an array of FlashcardKanji objects
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

//    public function findOneBySomeField($value): ?FlashcardKanji
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
