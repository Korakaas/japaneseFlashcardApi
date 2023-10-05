<?php

namespace App\Controller;

use App\Entity\User;
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
    public function __construct(
        private DeckRepository $deckRepository,
        private  ReviewRepository $reviewRepository,
        private AccessService $accessService,
        private FlashcardService $flashcardService,
        private ReviewService $reviewService,
    ) {}

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

        /**
         * @var User
         */
        $user = $this->getUser();
        //Verifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);


        //Vérifie que le paquet appartient bien à l'utilisateur
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        $this->accessService->checkDeckAccess($deck, $user);

        //Vérifie que la carte appartient bien au deck
        $flashcardId = $request->get('flashcardId');
        $flashcardReviewed = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flashcardId);

        //Traitement du score et ajustement de l'interval entre les révisions
        $score = $request->toArray()['score'] ?? null;
        $review = $this->reviewRepository->findOneBy(['user' => $user->getId(), 'flashcard' => $flashcardId]);
        $this->reviewService->updateReview($score, $flashcardReviewed, $review, $user);

        return new JsonResponse(
            'Les résultats ont bien été enregistrées',
            Response::HTTP_NO_CONTENT,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
