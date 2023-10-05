<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardModification;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerService
{
    public function __construct(private SerializerInterface $serializer) {}

    /**
     * Convertit un objet Deck en JSON
     *
     * @param Deck $deck
     * @param string $serializationGroups
     * @return string
     */
    public function serializeDeck(Deck $deck, string $serializationGroups): string
    {
        return $this->serializer->serialize($deck, 'json', ['groups' => $serializationGroups]);
    }

    /**
     * Convertit un objet Flashcard en JSON
     *
     * @param Flashcard $Flashcard
     * @param string $serializationGroups
     * @return string
     */
    public function serializeFlashcard(Flashcard $Flashcard, string $serializationGroups): string
    {
        return $this->serializer->serialize($Flashcard, 'json', ['groups' => $serializationGroups]);
    }

    /**
     * Convertit un objet FlashcardModification en JSON
     *
     * @param FlashcardModification $Flashcard
     * @param string $serializationGroups
     * @return string
     */
    public function serializeFlashcardModification(
        FlashcardModification $Flashcard,
        string $serializationGroups
    ): string {
        return $this->serializer->serialize($Flashcard, 'json', ['groups' => $serializationGroups]);
    }
}
