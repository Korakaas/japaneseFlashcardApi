<?php

namespace App\Repository;

use App\Entity\FlashcardModification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashcardModification>
 *
 * @method FlashcardModification|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashcardModification|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashcardModification[]    findAll()
 * @method FlashcardModification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashcardModificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashcardModification::class);
    }

//    /**
//     * @return FlashcardModification[] Returns an array of FlashcardModification objects
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

//    public function findOneBySomeField($value): ?FlashcardModification
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
