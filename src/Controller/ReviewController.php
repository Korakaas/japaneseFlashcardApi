<?php

namespace App\Controller;

use App\Repository\DeckRepository;
use App\Repository\ReviewRepository;
use App\Service\AccessService;
use App\Service\FlashcardService;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/api", "api_")]
class ReviewController extends AbstractController
{
    private $deckRepository;
    private $reviewRepository;
    private $accessService;
    private $flashcardService;
    private $reviewService;


    public function __construct(
        DeckRepository $deckRepository,
        ReviewRepository $reviewRepository,
        AccessService $accessService,
        FlashcardService $flashcardService,
        ReviewService $reviewService,
    ) {
        $this->deckRepository = $deckRepository;
        $this->reviewRepository = $reviewRepository;
        $this->accessService = $accessService;
        $this->reviewService = $reviewService;
        $this->flashcardService = $flashcardService;
    }

    /**
     * Traite la révision de la carte en fonction de score de l'utilisateur
     *
     * @param Request $request
     * @throws \HttpException si l'user est inconnu/n'a pas accès au flashcard/score null
     * @return JsonResponse
     */
    #[Route('/user/decks/{deckId}/flashcards/{flashcardId}/review', name: "reviewFlashcard", methods:['POST'])]
    public function reviewFlashcard(
        Request $request,
    ): JsonResponse {

        //on verifie que l'utilisateur est connecté
        $user = $this->getUser();
        $this->accessService->handleNoUser($user);

        $deckId = $request->get('deckId');
        $flashcardId = $request->get('flashcardId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);

        //on vérifie que le paquet appartient bien à l'utilisateur
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

        $score = $request->toArray()['score'] ?? null;
        $flashcardReviewed = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flashcardId);
        $review = $this->reviewRepository->findOneBy(['user' => $user->getId(), 'flashcard' => $flashcardId]);

        //Traitement du score et ajsutement de la prochaine révision en fonction
        $this->reviewService->updateReview($score, $flashcardReviewed, $review, $user);

        return new JsonResponse(
            [],
            Response::HTTP_NO_CONTENT,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
