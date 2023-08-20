<?php

namespace App\Entity;

use App\Repository\FlashcardVocabularyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FlashcardVocabularyRepository::class)]
class FlashcardVocabulary extends Flashcard
{
    #[ORM\Column(length: 10)]
    #[Groups(["getDetailDeck"])]
    private ?string $word = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?string $audio = null;

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): static
    {
        $this->word = $word;

        return $this;
    }

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
}
