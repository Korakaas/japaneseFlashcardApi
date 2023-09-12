<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\User;
use App\Repository\DeckRepository;
use App\Service\AccessService;
use App\Service\DeckService;
use App\Service\FlashcardModificationService;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api", "api_")]
class DeckController extends AbstractController
{
    private $deckRepository;
    private $em;
    private $accessService;
    private $flashcardModificationService;
    private $deckService;
    private $serializer;


    public function __construct(
        DeckRepository $deckRepository,
        AccessService $accessService,
        FlashcardModificationService $flashcardModificationService,
        DeckService $deckService,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->deckRepository = $deckRepository;
        $this->em = $em;
        $this->accessService = $accessService;
        $this->deckService = $deckService;
        $this->flashcardModificationService = $flashcardModificationService;
        $this->serializer = $serializer;
    }

    /**
     * Retourne le nom de tous les decks publics
     *
     * @return JsonResponse
     */
    #[Route('/decks', name: 'decks', methods: ['GET'])]
    public function getDeckList(Request $request, PaginatorInterface $paginator): JsonResponse
    {

        $pagination = $paginator->paginate(
            $this->deckRepository->paginationquery(),
            $request->get('page', 1),
            $request->get('limit', 9),
        );
        $pagination->getTotalItemCount();

        return $this->json(
            [
                'decks' => $pagination->getItems(),
                'page' => $pagination->getCurrentPageNumber(),
                'total_items' => $pagination->getTotalItemCount(),
            ],
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Retourne le détail d'un deck avec une flashcard exemple
     *
     * @param Deck $deck
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/decks/{id}', name: 'detailDeck', methods: ['GET'])]
    public function getDetailDeck(
        Deck $deck,
        SerializerService $serializerService
    ): JsonResponse {

        //Verifie que le paquet est bien public
        $this->accessService->checkDeckPublic($deck);

        $jsonDeck =  $serializerService->serializeDeck(
            $deck,
            'getDetailDeck'
        );

        $deckArray = json_decode($jsonDeck, true);

        //on garde uniquement la première carte du paquet avec les modfications liées au deck s'il y en a
        $flashcardToKeep =  array_shift($deckArray['flashcards']);
        $flashcardToKeep = $this->flashcardModificationService->getFlashcardModification(
            $flashcardToKeep,
            $deck
        );
        $deckArray['flashcards'] = [$flashcardToKeep];

        return $this->json(
            $deckArray,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Retourne le nom de tous les decks de l'utilisateur
     *
     * @throws \HttpException si l'user est inconnu
     * @return JsonResponse
     */
    #[Route('/user/decks', name: 'userDecks', methods: ['GET'])]
    public function getUserDecks(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $pagination = $paginator->paginate(
            $this->deckRepository->paginationqueryUser($user),
            $request->get('page', 1),
            $request->get('limit', 9),
        );
        $pagination->getTotalItemCount();

        return $this->json(
            [
                'decks' => $pagination->getItems(),
                'page' => $pagination->getCurrentPageNumber(),
                'total_items' => $pagination->getTotalItemCount(),
            ],
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Retourne le détail d'un deck de l'utilisateur*
     *
     * @param Deck $deck
     * @param DeckService $deckService
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}', name: 'userDetailDeck', methods: ['GET'])]
    public function getUserDetailDeck(
        Deck $deck,
        SerializerService $serializerService
    ): JsonResponse {

        $jsonDeck =  $serializerService->serializeDeck(
            $deck,
            'getDetailDeck'
        );

        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deck, $user);

        //récupère les modifications liées au paquet de chaque carte
        $flashcards = [];
        foreach($deck->getFlashcards() as $flashcard) {
            $flashcard = $flashcard->toArray();
            $flashcard = $this->flashcardModificationService->getFlashcardModification(
                $flashcard,
                $deck
            );
            $flashcards[] = $flashcard;
        }

        $deckArray = json_decode($jsonDeck, true);
        $deckArray['flashcards'] = $flashcards;

        return $this->json(
            $deckArray,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    /**
     * Supprime un deck de l'utilisateur
     *
     * @param Deck $deckToDelete
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}', name: 'deleteDeck', methods: ['DELETE'])]
    public function deleteDeck(Deck $deckToDelete): JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deckToDelete, $user);

        $this->em->remove($deckToDelete);
        $this->em->flush();

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );

    }

    /**
     * Permet à l'utilisateur de créer un deck
     *
     * @param Request $request
     * @throws \HttpException si
     *  -l'user est inconnu
     *  -l'user n'a pas accès au deck
     *  -les données du formulaire sont incorrectes
     * @return JsonResponse
     */
    #[Route('/user/decks', name: "createDeck", methods: ['POST'])]
    public function createDeck(
        Request $request,
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        //vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        $deck = $this->serializer->deserialize($request->getContent(), Deck::class, 'json');
        $deck->setUser($user);

        //validation des données
        $this->deckService->validateDeck($deck);

        $this->em->persist($deck);
        $this->em->flush();

        $deckArray = $deck->toArray();

        return $this->json(
            $deckArray,
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );

    }

    /**
     * Permet à l'utilisateur de modifier un de ses decks
     *
     * @param Deck $deckToUpdate
     * @param Request $request
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/user/decks/{id}', name: "updateDeck", methods:['PUT'])]
    public function updateDeck(
        Request $request,
        Deck $deckToUpdate,
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        //Vérifie que le paquet appartient bien à l'utilisateur
        $this->accessService->checkDeckAccess($deckToUpdate, $user);

        $data = $request->toArray();

        if (isset($data['name'])) {
            $deckToUpdate->setName($data['name']);
        }
        if (isset($data['description'])) {
            $deckToUpdate->setDescription($data['description']);
        }
        if (isset($data['public'])) {
            $deckToUpdate->setPublic($data['public']);
        }
        if (isset($data['reverse'])) {
            $deckToUpdate->setReverse($data['reverse']);
        }

        //validation des données
        $this->deckService->validateDeck($deckToUpdate);

        $this->em->persist($deckToUpdate);
        $this->em->flush();

        $deckArray = $deckToUpdate->toArray();

        return new JsonResponse(
            $deckArray,
            JsonResponse::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );

    }

    /**
     * Permet à l'utilisateur connecté de copier le deck d'un autre utilisateur
     *
     * @param Deck $deckToCopy
     * @param Request $request
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/duplicate/decks/{id}', name: "duplicateDeck", methods:['POST'])]
    public function duplicateDeck(
        Deck $deckToCopy,
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        //Vérifie que l'utilisateur existe
        $this->accessService->handleNoUser($user);

        //Vérifie que le paquet n'appartient pas déjà à l'utilisateur
        $this->deckService->checkDeckToDuplicateUser($deckToCopy, $user);

        $newDeck = new Deck();

        $newDeck->setName($deckToCopy->getName());
        $newDeck->setDescription($deckToCopy->getDescription());
        $newDeck->setPublic($deckToCopy->isPublic());
        $newDeck->setReverse($deckToCopy->isReverse());
        $newDeck->setUser($user);

        $flaschards = $deckToCopy->getFlashcards();

        foreach($flaschards as $flaschard) {
            $newDeck->addFlashcard($flaschard);
            $flaschard->setDuplicate(true);
        }

        //validation des données
        $this->deckService->validateDeck($newDeck);

        $this->em->persist($newDeck);
        $this->em->flush();

        $deckArray = $newDeck->toArray();

        return new JsonResponse(
            $deckArray,
            JsonResponse::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
