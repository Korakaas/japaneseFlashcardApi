<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

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

    #[Route('/api/decks', name: 'decks', methods: ['GET'])]
    public function getDeckList(): JsonResponse
    {
        $deckList = $this->deckRepository->findAll();

        // Serialize the deck list to JSON with pretty print option
        $jsonDeckList = $this->serializer->serialize($deckList, 'json', [
            'groups' => 'getDecks',
            'json_encode_options' => JSON_PRETTY_PRINT
        ]);

        // Create the JSON response with correct status code and headers
        return new JsonResponse($jsonDeckList, Response::HTTP_OK, [], true);
    }
    
    #[Route('/api/decks/{id}', name: 'detailDeck', methods: ['GET'])]
    public function getDetailDeck(Deck $deck): JsonResponse
    {
        $jsondeck = $this->serializer->serialize($deck, 'json', [
            'groups' => 'getDecks',
            'json_encode_options' => JSON_PRETTY_PRINT
        ]);
        return new JsonResponse($jsondeck, Response::HTTP_OK, [], true);
    }

    #[Route('/api/decks/{id}', name: 'deleteDeck', methods: ['DELETE'])]
    public function deleteDeck(Deck $deck): JsonResponse
    {
        $this->em->remove($deck);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/decks', name:"createdeck", methods: ['POST'])]
    public function createdeck(
        Request $request,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse
    {

        $deck = $this->serializer->deserialize($request->getContent(), Deck::class, 'json');
        $this->em->persist($deck);
        $this->em->flush();

        $jsonDeck = $this->serializer->serialize($deck, 'json', [
            'groups' => 'getDecks',
            'json_encode_options' => JSON_PRETTY_PRINT
        ]);
        
        $location = $urlGenerator->generate('detailDeck', ['id' => $deck->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonDeck, Response::HTTP_CREATED, ["Location" => $location], true);
   }
}
