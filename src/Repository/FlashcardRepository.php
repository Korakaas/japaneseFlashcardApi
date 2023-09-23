<?php

namespace App\Repository;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\Review;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
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

    public function paginationquery(int $deckId, int $userId): Query
    {
        return $this->createQueryBuilder('f')
            ->select('f.id', 'f.front', 'r.intervalReview', 'r.reviewedAt', 'r.knownLevel')
            ->join('f.decks', 'd')
            ->join('f.user', 'u')
            // Utilisation de leftJoin pour récupérer les cartes qui n'ont pas encore de review
            ->leftjoin('f.reviews', 'r')
            ->andWhere('d.id = :deckId')
            ->andWhere('u.id = :userId')
            ->andWhere("(r.user = :userId) OR (r.user IS NULL) ")
            ->setParameter('deckId', $deckId)
            ->setParameter('userId', $userId)
            ->getQuery();
    }

    /**
     * Retourne une carte en fonction de son id et du deck auxquelle elle appartient
     *
     * @param int $deckId
     * @param int $flashcardId
     * @return Flashcard|null
     */
    public function findOneByIdAndDeck(int $deckId, int $flashcardId): ?Flashcard
    {
        return $this->createQueryBuilder('f')
        ->innerJoin('f.decks', 'd')
        ->where('f.id = :flashcardId')
        ->andWhere('d.id = :deckId')
        ->setParameter('flashcardId', $flashcardId)
        ->setParameter('deckId', $deckId)
        ->getQuery()
        ->getOneOrNullResult();
    }

    /**
     * Retourne 20 cartes à réviser
     * Les cartes sont séléctionnées en fonction de l'utilisateur,
     * du deck auxquelle apparitent la carte
     * et dont la date de révsion date de plus longtemps que l'interval enregistré
     *
     * @param int $deckId Le deck de la carte
     * @param int $userId L'utilisateur connecté
     * @param DateTime $todayDate La date du jour
     * @param int $limit Le nombre de carte max à retourner
     * @return array|null
     */
    public function findByToReview(int $deckId, int $userId, DateTime $todayDate, $limit = 20): ?array
    {
        $qb = $this->createQueryBuilder('f');
        $cards = $qb
        ->select('f')
        ->innerJoin('f.decks', 'd')
        ->innerJoin('f.user', 'u')
        // Utilisation de leftJoin pour récupérer les cartes qui n'ont pas encore de review
        ->leftJoin('f.reviews', 'r')
        ->where('u.id = :userId')
        ->andWhere('d.id = :deckId')
        ->andWhere("((r.reviewedAt IS NULL) OR (DATE_ADD(r.reviewedAt, r.intervalReview, 'DAY') < :todayDate))")
        ->andWhere("(r.user = :userId) OR (r.user IS NULL) ")
        ->setMaxResults($limit)
        ->setParameter('deckId', $deckId)
        ->setParameter('userId', $userId)
        ->setParameter('todayDate', $todayDate)
        ->getQuery()
        ->getResult();

        // Requête pour obtenir la somme du nombre de cartes
        $totalCardCount = $qb
        ->select('SUM(1) as totalCardCount')
        ->getQuery()
        ->getSingleScalarResult();
        return [
            'cards' => $cards,
            'totalCardCount' => $totalCardCount,
        ];
    }


}
