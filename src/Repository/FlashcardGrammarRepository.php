<?php

namespace App\Repository;

use App\Entity\FlashcardGrammar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashcardGrammar>
 *
 * @method FlashcardGrammar|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashcardGrammar|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashcardGrammar[]    findAll()
 * @method FlashcardGrammar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashcardGrammarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashcardGrammar::class);
    }

//    /**
//     * @return FlashcardGrammar[] Returns an array of FlashcardGrammar objects
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

//    public function findOneBySomeField($value): ?FlashcardGrammar
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
