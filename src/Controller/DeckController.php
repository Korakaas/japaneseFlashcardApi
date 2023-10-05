<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\User;
use App\Repository\DeckRepository;
use App\Service\AccessService;
use App\Service\DeckService;
use App\Service\FlashcardModificationService;
use App\Service\SerializerService;
use App\Service\ValidationService;
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
    public function __construct(
        private DeckRepository $deckRepository,
        private AccessService $accessService,
        private FlashcardModificationService $flashcardModificationService,
        private DeckService $deckService,
        private SerializerInterface $serializer,
        private EntityManagerInterface $em,
        private ValidationService $validationService
    ) {}

    /**
     * Retourne le nom et pseudo du créateur de tous les decks publics de manière paginé
     *
     * @return JsonResponse
     */
    #[Route('/decks', name: 'decks', methods: ['GET'])]
    public function getDeckList(Request $request, PaginatorInterface $paginator): JsonResponse
    {
        $pagination = $paginator->paginate(
            $this->deckRepository->paginationqueryDeck(),
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
     * Retourne tous les decks de l'utilisateur de manière paginée
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
            $this->deckRepository->paginationqueryDeckUser($user),
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
     * Retourne le détail d'un deck de l'utilisateur
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
            'Le paquet a bien été supprimé',
            Response::HTTP_OK,
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
        $this->validationService->validateDeck($deck);

        $this->em->persist($deck);
        $this->em->flush();

        return $this->json(
            'Le paquet a bien été crée',
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

        //validation des données
        $this->validationService->validateDeck($deckToUpdate);

        $this->em->persist($deckToUpdate);
        $this->em->flush();

        return $this->json(
            'Le paquet a bien été modifié',
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
        //par défaut on set public à false parce que ce cn'est pas intéressant
        //d'avoir 2 fois exactement le même paquet à proposer
        $newDeck->setPublic(false);
        $newDeck->setUser($user);

        $flaschards = $deckToCopy->getFlashcards();

        foreach($flaschards as $flaschard) {
            $newDeck->addFlashcard($flaschard);
            $flaschard->setDuplicate(true);
        }

        //validation des données
        $this->validationService->validateDeck($newDeck);

        $this->em->persist($newDeck);
        $this->em->flush();

        $deckArray = $newDeck->toArray();

        return $this->json(
            'Le paquet a bien été rajoutée à votre compte',
            JsonResponse::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }
}
