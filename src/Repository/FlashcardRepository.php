<?php

namespace App\Repository;

use App\Entity\Deck;
use App\Entity\Flashcard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Array_;

/**
 * @extends ServiceEntityRepository<Flashcard>
 *
 * @method Flashcard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flashcard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flashcard[]    findAll()
 * @method Flashcard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashcardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flashcard::class);
    }

    //    /**
    //     * @return Flashcard[] Returns an array of Flashcard objects
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

    public function findOneByDeck($deckId): ?array
    {
        return $this->createQueryBuilder('f')
            ->select('f.translation', 'f.furigana', 'f.example', ' f.createdAt', 'f.updatedAt')
            ->join('f.decks', 'd')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }
}
