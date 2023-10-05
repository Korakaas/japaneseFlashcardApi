<?php

namespace App\Service;

use App\Entity\DailyStats;
use App\Entity\Deck;
use App\Entity\User;
use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class DailyStatsService
{
    private const KNOWN_LEVEL_LIMIT = 8;
    private const NEW_LEVEL_LIMIT = 3;

    public function __construct(private EntityManagerInterface $em, private ReviewRepository $reviewRepository) {}

    /**
     * Retourne les statistiques d'un paquet
     *
     * @param User $user
     * @param Deck $deck
     * @return array les statistiques du paquet
     */
    public function getDeckStats(User $user, Deck $deck): array
    {
        $deckStats = [
            'deckName' => $deck->getName(),
            'dailyStats' => [],
            'flashcards' => [
                'known' => 0,
                'new' => 0,
                'learning' => 0,
            ],
        ];

        foreach ($deck->getDailyStats() as $dailyStat) {
            $deckStats['dailyStats'][] = [
                'date' => $dailyStat->getDate(),
                'reviewNumber' => $dailyStat->getFlashcardsReviewed(),
                'correctAnswer' => $dailyStat->getCorrectAnswers(),
            ];
        }

        foreach ($deck->getFlashcards() as $flashcard) {
            $review = $this->reviewRepository->findOneBy(
                ['user' => $user->getId(), 'flashcard' => $flashcard->getId()]
            );

            if ($review) {
                $knownLevel = $review->getKnownLevel();
                if ($knownLevel > DailyStatsService::KNOWN_LEVEL_LIMIT) {
                    $deckStats['flashcards']['known']++;
                } elseif ($knownLevel <= DailyStatsService::NEW_LEVEL_LIMIT) {
                    $deckStats['flashcards']['new']++;
                } else {
                    $deckStats['flashcards']['learning']++;
                }
            } else {
                $deckStats['flashcards']['new']++;
            }
        }

        return $deckStats;
    }

    /**
     * Met à jour les statistiques quotidiennes du deck
     *
     * @param DateTimeImmutable $today
     * @param array $request le score de révision
     * @param Deck $deck
     * @return void
     */
    public function updateDailyStats(
        DateTimeImmutable $today,
        array $request,
        Deck $deck
    ): void {
        $createdNewDailyStat = true;
        $dailyStats = $deck->getDailyStats();

        $score = $request['score'] ?? null;
        $min = 1;
        $max = 5;

        foreach ($dailyStats as $dailyStat) {
            if ($dailyStat->getDate()->format('Y-m-d') === $today->format('Y-m-d')) {
                $dailyStat->setFlashcardsReviewed($dailyStat->getFlashcardsReviewed() + 1);

                if ($score !== null && $score >= $min && $score <= $max && $score >= 3) {
                    $dailyStat->setCorrectAnswers($dailyStat->getCorrectAnswers() + 1);
                }

                $createdNewDailyStat = false;
                break;
            }
        }

        if ($createdNewDailyStat) {
            $dailyStat = new DailyStats();
            $dailyStat->setDeck($deck);
            $dailyStat->setDate($today);
            $dailyStat->setFlashcardsReviewed(1);

            if ($score !== null && $score >= $min && $score <= $max && $score >= 3) {
                $dailyStat->setCorrectAnswers(1);
            }
            $this->em->persist($dailyStat);
        }
    }
}
