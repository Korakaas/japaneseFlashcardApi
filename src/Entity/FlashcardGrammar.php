<?php

namespace App\Entity;

use App\Repository\FlashcardGrammarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardGrammarRepository::class)]
class FlashcardGrammar extends Flashcard
{
    #[ORM\Column(length: 255)]
    #[Groups(["getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'construction du point de grammaire' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $construction = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailFlashcard"])]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le champ 'notes' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $grammarnotes = null;

    public function getConstruction(): ?string
    {
        return $this->construction;
    }

    public function setConstruction(string $construction): static
    {
        $this->construction = $construction;

        return $this;
    }

    public function getGrammarnotes(): ?string
    {
        return $this->grammarnotes;
    }

    public function setGrammarnotes(?string $grammarnotes): static
    {
        $this->grammarnotes = $grammarnotes;

        return $this;
    }
}
