<?php

namespace App\Entity;

use App\Repository\FlashcardKanjiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardKanjiRepository::class)]
class FlashcardKanji extends Flashcard
{
    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(["getDetailFlashcard"])]
    #[Assert\Length(
        max: 60,
        maxMessage: "Le champ 'Onyomi' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $onyomi = null;

    #[ORM\Column(length: 60, nullable: true)]
    #[Groups(["getDetailFlashcard"])]
    #[Assert\Length(
        max: 60,
        maxMessage: "Le champ 'Kunyomi' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $kunyomi = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailFlashcard"])]
    #[Assert\Length(
        max: 60,
        maxMessage: "Le champ 'moyen mnémotechniques' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $mnemotic = null;

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

    public function getmnemotic(): ?string
    {
        return $this->mnemotic;
    }

    public function setMnemotic(?string $mnemotic): static
    {
        $this->mnemotic = $mnemotic;

        return $this;
    }
}
