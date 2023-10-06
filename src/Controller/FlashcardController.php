<?php

namespace App\Controller;

use App\Entity\Flashcard;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use App\Repository\DeckRepository;
use App\Repository\FlashcardRepository;
use App\Service\AccessService;
use App\Service\FlashcardModificationService;
use App\Service\FlashcardService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api", "api_")]
class FlashcardController extends AbstractController
{
    public function __construct(
        private DeckRepository $deckRepository,
        private FlashcardRepository $flashcardRepository,
        private AccessService $accessService,
        private FlashcardService $flashcardService,
        private FlashcardModificationService $flashcardModificationService,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private ValidationService $validationService
    ) {}

    /**
     * Retourne le nom et les données de révision de toutes les flashcards de l'utilisateur
     *
     * @throws \HttpException si l'user est inconnu
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}/flashcards', name: 'userFlashcards', methods: ['GET'])]
    public function getUserFlashcards(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);
        //Vérifie que le paquet appartient bien à l'utilisateur
        $deckId = $request->get('id');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        $this->accessService->checkDeckAccess($deck, $user);

        $pagination = $paginator->paginate(
            $this->flashcardRepository->paginationqueryFlashcard($deckId, $user->getId()),
            $request->get('page', 1),
            $request->get('limit', 10),
        );
        $pagination->getTotalItemCount();

        return $this->json(
            [
                'flashcards' => $pagination->getItems(),
                'page' => $pagination->getCurrentPageNumber(),
                'total_items' => $pagination->getTotalItemCount(),
            ],
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Retourne le détail d'une flashcard de l'utilisateur
     *
     * @param Flashcard $flashcard
     * @param FlashcardService $flashcardService
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au flashcard
     * @return JsonResponse
     */
    #[Route('/user/decks/{deckId}/flashcards/{flashcardId}', name: 'userDetailFlashcard', methods: ['GET'])]
    public function getUserDetailFlashcard(
        Request $request,
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        //vérfie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);

        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deck, $user);

        //Verifie que la carte appartient bien au paquet
        $flashcardToReturn = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        //récupère le type de la carte
        $flashcardType = $this->flashcardService->getFlashcardType($flashcardToReturn);


        //récupère les modifications liées au deck de la carte
        $flashcardToReturn = $this->flashcardModificationService->getFlashcardModification(
            $flashcardToReturn->toArray(),
            $deck
        );
        $flashcardToReturn['type'] = $flashcardType;


        return new JsonResponse(
            $flashcardToReturn,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Supprime une flashcard de l'utilisateur ou ses modifications
     *
     * @param Flashcard $flashcardToDelete
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au flashcard
     * @return JsonResponse
     */
    #[Route('/user/decks/{deckId}/flashcards/{flashcardId}', name: 'deleteFlashcard', methods: ['DELETE'])]
    public function deleteFlashcard(Request $request): JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);

        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deck, $user);

        //Vérifie que la carte appartient bien au parquet
        $flashcardToDelete = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        //Supprime la carte ou les modifications selon si carte est dupliquée ou non
        $this->flashcardService->deleteFlashcard($flashcardToDelete, $user, $deck);
        return $this->json(
            'La carte a bien été supprimée',
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Permet à l'utilisateur de créer une flashcard
     *
     * @param Flashcard $flashcard
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au flashcard
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}/flashcards', name: "createFlashcard", methods: ['POST'])]
    public function createFlashcard(
        Request $request,
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $deckId = $request->get('id');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deck, $user);
        $data = json_decode($request->getContent(), true);
        $reverse = $data['reverse'];

        switch ($data['type']) {
            case 'grammar':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardGrammar::class, 'json');
                break;
            case 'kanji':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardKanji::class, 'json');
                break;
            case 'vocabulary':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardVocabulary::class, 'json');
                break;
            default:
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Requête invalide');
        }
        if($reverse) {
            $flashcardBack = clone $flashcard;
            $flashcardBack->setFront($flashcard->getBack());
            $flashcardBack->setBack($flashcard->getFront());
            $flashcardBack->addDeck($deck);
            $flashcardBack->addUser($user);
            $this->em->persist($flashcardBack);
            $this->validationService->validateFlashcard($flashcardBack);
        }
        $flashcard->addDeck($deck);
        $flashcard->addUser($user);




        //Vérifie que les données sont valides
        $this->validationService->validateFlashcard($flashcard);

        $this->em->persist($flashcard);
        $this->em->flush();

        return new JsonResponse('La carte a bien été ajoutée', Response::HTTP_CREATED);

    }

    /**
     * Permet à l'utilisateur de modifier une de ses flashcards
     *
     * @param Flashcard $flashcardToUpdate
     * @param Request $request
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au flashcard
     * @return JsonResponse
     */
    #[Route('/user/decks/{deckId}/flashcards/{flashcardId}', name: "updateFlashcard", methods:['PUT'])]
    public function updateFlashcard(
        Request $request,
    ): JsonResponse {
        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deck, $user);
        $data = $request->toArray();

        //Vérifie que la carte appartient bien au deck
        $flashcardToUpdate = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        if(!$flashcardToUpdate->isDuplicate()) {
            $this->flashcardService->updateFlashcardProperties($flashcardToUpdate, $data);
            $this->validationService->validateFlashcard($flashcardToUpdate);

        } else {
            //si lacarte appartient à plusieurs utilisateurs, on ne modifie pas directement
            //la carte mais on enregistre les modifcations à part
            $flashcardModification = $this->flashcardModificationService->setFlashcardModificationData(
                $flashcardToUpdate,
                $deck,
                $data,
                $user,
            );
            $this->validationService->validateFlashcardModification($flashcardModification);
        }

        $this->em->flush();

        return new JsonResponse('La carte a bien été modifée', JsonResponse::HTTP_OK);
    }


    /**
     * Retourne un carte du deck à réviser
     *
     * @param Flashcard $flashcardToUpdate
     * @param Request $request
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au flashcard
     * @return JsonResponse
     */
    #[Route('/user/decks/{deckId}/test', name: "testFlashcard", methods:['GET'])]
    public function getFlashcardForTest(
        Request $request,
    ): JsonResponse {
        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);

        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deck, $user);

        //Récupère les cartes dont la date de révision est supérieur à celle du jour
        $toReview = $this->flashcardService->findFlashcardToreview(
            $deckId,
            $user->getId()
        );
        $flaschardToReviewArray = $toReview['cards'];
        $sumFlashcards = $toReview['totalCardCount'];

        //on garde 1 carte au hasard
        $flaschardToReview = $flaschardToReviewArray[array_rand($flaschardToReviewArray)];

        $flashcardType = $this->flashcardService->getFlashcardType($flaschardToReview);

        //récupère les modifications liées au deck de la carte
        $flaschardToReview = $flaschardToReview->toArray();
        $flaschardToReview = $this->flashcardModificationService->getFlashcardModification(
            $flaschardToReview,
            $deck
        );
        $flaschardToReview['type'] = $flashcardType;

        $result = [
            'cards' => $flaschardToReview,
            'totalCardCount' => $sumFlashcards
        ];

        return new JsonResponse(
            $result,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
