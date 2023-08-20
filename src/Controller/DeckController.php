<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardKanji;
use App\Repository\DeckRepository;
use App\Repository\FlashcardKanjiRepository;
use App\Repository\FlashcardRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api", "api_")]
class DeckController extends AbstractController
{
    private $deckRepository;
    private $serializer;
    private $em;

    public function __construct(
        DeckRepository $deckRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ) {
        $this->deckRepository = $deckRepository;
        $this->serializer = $serializer;
        $this->em = $em;
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
        $deckNames = [];
        foreach ($deckList as $deck) {
            $deckNames[] = $deck->getName();
        }
        // dd($deckList);
        // Create the JSON response with correct status code and headers
        return $this->json(
            $deckNames,
            Response::HTTP_OK,
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
    }

    #[Route('/decks/{id}', name: 'detailDeck', methods: ['GET'])]
    public function getDetailDeck(
        Deck $deck,
        SerializerInterface $serializer,
    ): JsonResponse {
        $jsonDeck = $serializer->serialize(
            $deck,
            'json',
            ['groups' => 'getDetailDeck', 'json_encode_options' => JSON_PRETTY_PRINT]
        );

        $deckArray = json_decode($jsonDeck, true);
        $flashcardToKeep =  array_shift($deckArray['flashcards']);
        $deckArray['flashcards'] = [$flashcardToKeep];
        $jsonDeck = json_encode($deckArray, JSON_PRETTY_PRINT);
        return new JsonResponse(
            $jsonDeck,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/decks/{id}', name: 'deleteDeck', methods: ['DELETE'])]
    public function deleteDeck(Deck $deck): JsonResponse
    {
        $this->em->remove($deck);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/decks', name:"createdeck", methods: ['POST'])]
    public function createdeck(
        Request $request,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {

        $user = $this->getUser();
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
}
