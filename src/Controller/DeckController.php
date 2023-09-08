<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\User;
use App\Repository\DeckRepository;
use App\Service\DeckService;
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
class DeckController extends AbstractController
{
    private $deckRepository;
    private $em;
    private $userService;
    private $serializer;


    public function __construct(
        DeckRepository $deckRepository,
        UserService $userService,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->deckRepository = $deckRepository;
        $this->em = $em;
        $this->userService = $userService;
        $this->serializer = $serializer;
    }

    /**
     * Retourne le nom de tous les decks
     *
     * @return JsonResponse
     */
    #[Route('/decks', name: 'decks', methods: ['GET'])]
    public function getDeckList(): JsonResponse
    {
        $deckList = $this->deckRepository->findAll();
        $deckNames = array_map(fn ($deck) => $deck->getName(), $deckList);
        // dd($deckList);
        // Create the JSON response with correct status code and headers
        return $this->json(
            $deckNames,
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

        $jsonDeck =  $serializerService->serializeDeck(
            $deck,
            'getDetailDeck'
        );

        $deckArray = json_decode($jsonDeck, true);
        $flashcardToKeep =  array_shift($deckArray['flashcards']);
        $deckArray['flashcards'] = [$flashcardToKeep];
        $jsonDeck = json_encode($deckArray, JSON_PRETTY_PRINT);
        return new JsonResponse(
            $jsonDeck,
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
    public function getUserDecks(): JsonResponse
    {
        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);
        $deckList = $this->deckRepository->findBy(['user' => $user->getId()]);
        $deckNames = array_map(fn ($deck) => $deck->getName(), $deckList);

        return  new JsonResponse(
            $deckNames,
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
        $this->userService->handleNoUser($user);

        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }
        $deckArray = json_decode($jsonDeck, true);
        $jsonDeck = json_encode($deckArray, JSON_PRETTY_PRINT);
        return new JsonResponse(
            $jsonDeck,
            Response::HTTP_OK,
            ['Content-Type' => 'application/json;charset=UTF-8']
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
        $this->userService->handleNoUser($user);

        if ($deckToDelete->getUser() !== $user) {
            throw new HttpException(
                Response::HTTP_UNAUTHORIZED,
                'L\'utilisateur '.$user->getPseudo(). ' n\'a pas accès au deck'.$deckToDelete->getName()
            );
        }
        $this->em->remove($deckToDelete);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT, ['Content-Type' => 'application/json;charset=UTF-8']);

    }

    /**
     * Permet à l'utilisateur de créer un deck
     *
     * @param Deck $deck
     * @param Request $request
     * @param UrlGeneratorInterface $urlGenerator
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/decks', name: "createDeck", methods: ['POST'])]
    public function createDeck(
        Request $request,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);

        $deck = $this->serializer->deserialize($request->getContent(), Deck::class, 'json');
        $deck->setUser($user);
        $this->em->persist($deck);
        $this->em->flush();

        $location = $urlGenerator->generate(
            'api_detailDeck',
            ['id' => $deck->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $deckArray = $deck->toArray();

        return new JsonResponse($deckArray, Response::HTTP_CREATED, ["Location" => $location]);

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
        ValidatorInterface $validator
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);

        if ($deckToUpdate->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }

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

        $errors = $validator->validate($deckToUpdate, null, ['deck_update']);

        if (count($errors) > 0) {
            $errorsmessage = [];
            foreach ($errors as $error) {
                $errorsmessage[] = $error->getMessage();
            }
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errorsmessage));
        }

        $this->em->persist($deckToUpdate);
        $this->em->flush();

        $deckArray = $deckToUpdate->toArray();

        return new JsonResponse($deckArray, JsonResponse::HTTP_NO_CONTENT);

    }

    /**
     * Permet à l'utilisateur connecté de copier le deck d'un autre utilisateur
     *
     * @param Deck $deckToCopy
     * @param Request $request
     * @throws \HttpException si l'user est inconnu ou n'a pas accès au deck
     * @return JsonResponse
     */
    #[Route('/decks/{id}', name: "duplicateDeck", methods:['POST'])]
    public function duplicateDeck(
        Deck $deckToCopy,
    ): JsonResponse {

        /**
         * @var User
         */
        $user = $this->getUser();
        $this->userService->handleNoUser($user);


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

        $this->em->persist($newDeck);
        $this->em->flush();

        $deckArray = $newDeck->toArray();

        return new JsonResponse($deckArray, JsonResponse::HTTP_NO_CONTENT, ['Content-Type' => 'application/json;charset=UTF-8']);
    }
}
