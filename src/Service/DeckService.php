<?php

namespace App\Service;

use App\Entity\Deck;
use Symfony\Component\Serializer\SerializerInterface;

class DeckService
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
}
