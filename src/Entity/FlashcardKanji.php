<?php

namespace App\Entity;

use App\Repository\FlashcardKanjiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FlashcardKanjiRepository::class)]
class FlashcardKanji extends Flashcard
{
    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?string $onyomi = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(["getDetailDeck"])]
    private ?string $kunyomi = null;

    public function getOnyomi(): ?string
    {
        return $this->onyomi;
    }

    public function setOnyomi(?string $onyomi): static
    {
        $this->onyomi = $onyomi;

        return $this;
    }

    public function getKunyomi(): ?string
    {
        return $this->kunyomi;
    }

    public function setKunyomi(?string $kunyomi): static
    {
        $this->kunyomi = $kunyomi;

        return $this;
    }
}