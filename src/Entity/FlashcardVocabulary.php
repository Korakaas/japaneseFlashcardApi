<?php

namespace App\Entity;

use App\Repository\FlashcardVocabularyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardVocabularyRepository::class)]
class FlashcardVocabulary extends Flashcard
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'image' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'audio' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $audio = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'synonyme' ne peut pas faire plus de {{ limit }} caractères",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $synonym = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'antonyme' ne peut pas faire plus de {{ limit }} caractères",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $antonym = null;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(?string $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function getSynonym(): ?string
    {
        return $this->synonym;
    }

    public function setSynonym(?string $synonym): static
    {
        $this->synonym = $synonym;

        return $this;
    }

    public function getAntonym(): ?string
    {
        return $this->antonym;
    }

    public function setAntonym(?string $antonym): static
    {
        $this->antonym = $antonym;

        return $this;
    }
}
