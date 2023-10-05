<?php

namespace App\Repository;

use App\Entity\Deck;
use App\Entity\Flashcard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deck>
 *
 * @method Deck|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deck|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deck[]    findAll()
 * @method Deck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deck::class);
    }

    //    /**
    //     * @return Deck[] Returns an array of Deck objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Deck
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Récupère id,nom des paquets public et pseudo du créateur
     *
     * @return void
     */
    public function paginationqueryDeck()
    {
        return $this->createQueryBuilder('d')
            ->select('d.id', 'd.name', 'u.pseudo')
            ->where('d.public = 1')
            ->innerJoin('d.user', 'u')
            ->getQuery();
    }

    /**
     * Récupère les paquets de l'utilisateur
     *
     * @param [type] $user
     * @return void
     */
    public function paginationqueryDeckUser($user)
    {
        return $this->createQueryBuilder('d')
            ->select('d.id', 'd.name', 'd.description', 'd.createdAt', 'd.updatedAt', 'd.public')
            ->where('d.user = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }
}
