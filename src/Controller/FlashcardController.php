<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardConjugation;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardModification;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use App\Repository\DeckRepository;
use App\Repository\FlashcardModificationRepository;
use App\Repository\ReviewRepository;
use App\Service\FlashcardService;
use App\Service\SerializerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api", "api_")]
class FlashcardController extends AbstractController
{
    private $flashcardModificationRepository;
    private $deckRepository;
    private $reviewRepository;
    private $em;
    private $userService;
    private $flashcardService;
    private $serializer;


    public function __construct(
        FlashcardModificationRepository $flashcardModificationRepository,
        DeckRepository $deckRepository,
        ReviewRepository $reviewRepository,
        UserService $userService,
        FlashcardService $flashcardService,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->flashcardModificationRepository = $flashcardModificationRepository;
        $this->deckRepository = $deckRepository;
        $this->reviewRepository = $reviewRepository;
        $this->em = $em;
        $this->userService = $userService;
        $this->flashcardService = $flashcardService;
        $this->serializer = $serializer;
    }

    /**
     * Retourne toutes les flashcards
     *
     * @return JsonResponse
     */
    #[Route('/decks/{id}/flashcards', name: 'flashcards', methods: ['GET'])]
    public function getFlashcardList(Deck $deck): JsonResponse
    {
        $flashcardList = $deck->getFlashcards()->toArray();
        $flashcardNames = array_map(fn ($flashcard) => $flashcard->getTranslation(), $flashcardList);
        return $this->json(
            $flashcardNames,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Retourne le détail d'un flashcard
     *
     * @param Request $request
     * @param SerializerService $serializerService
     * @return JsonResponse
     */
    #[Route('/decks/{deckId}/flashcards/{flashcardId}', name: 'detailFlashcard', methods: ['GET'])]
    public function getDetailFlashcard(
        Request $request,
        SerializerService $serializerService
    ): JsonResponse {

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $flashcardToReturn = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        $flashcardToReturn = $flashcardToReturn->toArray();
        $flashcardModif = $this->flashcardModificationRepository->findOneBy(
            ['deck' => $deckId, 'flashcard' => $flaschardId]
        );
        if($flashcardModif) {
            $flashcardModif = $serializerService->serializeFlashcardModification($flashcardModif, 'getFlashcardModif');
            $flashcardModif = json_decode($flashcardModif, true);
            foreach($flashcardModif as $key => $value) {

                if($value && array_key_exists($key, $flashcardToReturn)) {
                    $flashcardToReturn[$key] = $value;
                }
            }
        }
        return new JsonResponse(
            $flashcardToReturn,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Retourne le nom de toutes les flashcards de l'utilisateur
     *
     * @throws \HttpException si l'user est inconnu
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}/flashcards', name: 'userFlashcards', methods: ['GET'])]
    public function getUserFlashcards(Deck $deck): JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

        $flashcardList = $deck->getFlashcards()->toArray();
        $flashcardNames = array_map(fn ($flashcard) => $flashcard->getTranslation(), $flashcardList);

        return  new JsonResponse(
            $flashcardNames,
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
        SerializerService $serializerService
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

        $flashcardToReturn = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        $flashcardToReturn = $flashcardToReturn->toArray();
        $flashcardModif = $this->flashcardModificationRepository->findOneBy(
            ['deck' => $deckId, 'flashcard' => $flaschardId]
        );
        if($flashcardModif) {
            $flashcardModif = $serializerService->serializeFlashcardModification($flashcardModif, 'getFlashcardModif');
            $flashcardModif = json_decode($flashcardModif, true);
            foreach($flashcardModif as $key => $value) {

                if($value && array_key_exists($key, $flashcardToReturn)) {
                    $flashcardToReturn[$key] = $value;
                }
            }
        }
        return new JsonResponse(
            $flashcardToReturn,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Supprime une flashcard de l'utilisateur
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
        $this->userService->handleNoUser($user);

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }
        $flashcardToDelete = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        $flashcardUsers = $flashcardToDelete->getUser();
        if($flashcardToDelete->isDuplicate() && count($flashcardUsers) > 1) {
            $flashcardModif = $this->flashcardModificationRepository->findOneBy(
                ['deck' => $deckId, 'flashcard' => $flaschardId]
            );
            if($flashcardModif) {
                $this->em->remove($flashcardModif);
            }

            $deck->removeFlashcard($flashcardToDelete);
            $flashcardToDelete->removeUser($user);
            if(count($flashcardUsers) === 1) {
                $flashcardToDelete->setDuplicate(false);
            }
        } else {
            $this->em->remove($flashcardToDelete);
        }
        $review = $this->reviewRepository->findOneBy(
            ['user' => $user->getId(), 'flashcard' => $flaschardId]
        );
        if($review) {
            $this->em->remove($review);
        }

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT, ['Content-Type' => 'application/json;charset=UTF-8']);

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
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);

        $deckId = $request->get('id');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

        $data = json_decode($request->getContent(), true);

        switch ($data['type']) {
            case 'grammar':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardGrammar::class, 'json');
                break;
            case 'kanji':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardKanji::class, 'json');
                break;
            case 'conjugation':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardConjugation::class, 'json');
                break;
            case 'vocabulary':
                $flashcard = $this->serializer->deserialize($request->getContent(), FlashcardVocabulary::class, 'json');
                break;
            default:
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Requête invalide');
        }

        $flashcard->addDeck($deck);

        $this->em->persist($flashcard);
        $this->em->flush();

        $location = $urlGenerator->generate(
            'api_detailFlashcard',
            ['id' => $deckId, 'flashcardId' => $flashcard->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $flashcardArray = $flashcard->toArray();

        return new JsonResponse($flashcardArray, Response::HTTP_CREATED, ["Location" => $location]);

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
        ValidatorInterface $validator
    ): JsonResponse {
        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);

        $flaschardId = $request->get('flashcardId');
        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

        $data = $request->toArray();

        $flashcardToUpdate = $this->flashcardService->findFlashcardByIdAndDeck($deckId, $flaschardId);

        if(!$flashcardToUpdate->isDuplicate()) {
            dd($flashcardToUpdate);
            $this->flashcardService->updateFlashcardProperties($flashcardToUpdate, $data);

        } else {
            $flashcardModification = new FlashcardModification();
            $this->flashcardService->updateFlashcardProperties($flashcardToUpdate, $data);

            $flashcardModification->setDeck($deck);
            $flashcardModification->setUser($user);
            $this->em->persist($flashcardModification);

        }

        $errors = $validator->validate($flashcardToUpdate, null);

        if (count($errors) > 0) {
            $errorsmessage = [];
            foreach ($errors as $error) {
                $errorsmessage[] = $error->getMessage();
            }
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errorsmessage));
        }

        $this->em->persist($flashcardToUpdate);
        $this->em->flush();

        $flashcardArray = $flashcardToUpdate->toArray();

        return new JsonResponse($flashcardArray, JsonResponse::HTTP_NO_CONTENT);

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
        SerializerService $serializerService
    ): JsonResponse {
        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);

        $deckId = $request->get('deckId');
        $deck = $this->deckRepository->findOneBy(['id' => $deckId]);
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

        $flaschardToReviewArray = $this->flashcardService->findFlashcardToreview((int) $deckId, $user->getId());

        $flaschardToReview = $flaschardToReviewArray[array_rand($flaschardToReviewArray)];

        $flashcardModif = $this->flashcardModificationRepository->findOneBy(['deck' => $deckId, 'flashcard' => $flaschardToReview->getId()]);
        $flaschardToReview = $flaschardToReview->toArray();
        if($flashcardModif) {
            $flashcardModif = $serializerService->serializeFlashcardModification($flashcardModif, 'getFlashcardModif');
            $flashcardModif = json_decode($flashcardModif, true);
            foreach($flashcardModif as $key => $value) {

                if($value && array_key_exists($key, $flaschardToReview)) {
                    $flaschardToReview[$key] = $value;
                }
            }
        }

        return new JsonResponse(
            $flaschardToReview,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
