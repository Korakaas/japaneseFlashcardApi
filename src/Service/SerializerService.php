<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardModification;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serializeDeck(Deck $deck, string $serializationGroups): string
    {
        return $this->serializer->serialize($deck, 'json', ['groups' => $serializationGroups]);
    }

    public function serializeFlashcard(Flashcard $Flashcard, string $serializationGroups): string
    {
        return $this->serializer->serialize($Flashcard, 'json', ['groups' => $serializationGroups]);
    }

    public function serializeFlashcardModification(FlashcardModification $Flashcard, string $serializationGroups): string
    {
        return $this->serializer->serialize($Flashcard, 'json', ['groups' => $serializationGroups]);
    }
}
