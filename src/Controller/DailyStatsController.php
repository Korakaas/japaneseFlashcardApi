<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\User;
use App\Repository\DeckRepository;
use App\Service\AccessService;
use App\Service\DailyStatsService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", "api_")]
class DailyStatsController extends AbstractController
{
    public function __construct(
        private DeckRepository $deckRepository,
        private AccessService $accessService,
        private DailyStatsService $dailyStatsService,
        private EntityManagerInterface $em
    ) {}

    /**
     * Retourne les statistiques globales de l'utilisateur
     *
     * @return JsonResponse
     */
    #[Route('/user/stats', name: 'allStats', methods: ['GET'])]
    public function getUserStats(): JsonResponse
    {

        /**
         * @var User
         */
        $user = $this->getUser();
        //vérifie si utilisateur existe
        $this->accessService->handleNoUser($user);
        $stats = [];

        //récupère les stats de chaque deck de l'utilisateur
        foreach ($user->getDecks() as $deck) {
            $deckStats = $this->dailyStatsService->getDeckStats($user, $deck);
            $stats[] = $deckStats;
        }

        return $this->json(
            $stats,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }


    /**
     * Retourne les statistiques d'un deck de l'utilisateur
     *
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}/stats', name: 'deckStats', methods: ['GET'])]
    public function getUserDeckStats(Deck $deck): JsonResponse
    {
        $user = $this->getUser();

        //vérifie si utilisateur existe
        $this->accessService->handleNoUser($user);

        //vérifie si utilisateur a accès au deck
        $this->accessService->checkDeckAccess($deck, $user);

        //récupère les stats  du deck
        $deckStats = $this->dailyStatsService->getDeckStats($user, $deck);

        return $this->json(
            $deckStats,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Enregistre les statistiques quotidiennes pour un deck de l'utilisateur.
     *
     * @param Deck $deck
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @throws HttpException si l'utilisateur est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/user/decks/{deckId}/stats', name: "saveStats", methods: ['POST'])]
    public function saveDeckStats(Request $request): JsonResponse
    {
        //Vérifie si l'utilisateur existe
        $user = $this->getUser();
        $this->accessService->handleNoUser($user);

        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);

        // Vérifie si l'utilisateur a accès au deck
        $this->accessService->checkDeckAccess($deck, $user);

        $today = new DateTimeImmutable();
        $request = $request->toArray();

        // Crée ou mettre à jour les statistiques quotidiennes
        $this->dailyStatsService->updateDailyStats($today, $request, $deck);

        // Enregistre les modifications dans la base de données
        $this->em->flush();

        return $this->json(
            'Statistiques misesà jour',
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
