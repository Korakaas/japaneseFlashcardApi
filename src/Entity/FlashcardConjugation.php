<?php

namespace App\Entity;

use App\Repository\FlashcardConjugationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlashcardConjugationRepository::class)]
class FlashcardConjugation extends Flashcard
{
    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme polie' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $polite = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme negative' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $negative = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme conditionnelle en Ba' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $conditionnalBa = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme contionnelle en Tara' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $conditionalTara = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme imperative' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $imperative = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme volitive' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $volitionnal = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme causatif' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $causative = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme potentiel' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $potential = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme en te' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $teForm = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Groups(["getDetailDeck", "getDetailFlashcard"])]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le champ 'forme en ta' ne peut pas faire plus de {{ limit }} caractères",
    )]
    private ?string $taForm = null;

    public function getPolite(): ?string
    {
        return $this->polite;
    }

    public function setPolite(string $polite): static
    {
        $this->polite = $polite;

        return $this;
    }

    public function getNegative(): ?string
    {
        return $this->negative;
    }

    public function setNegative(string $negative): static
    {
        $this->negative = $negative;

        return $this;
    }

    public function getConditionnalBa(): ?string
    {
        return $this->conditionnalBa;
    }

    public function setConditionnalBa(string $conditionnalBa): static
    {
        $this->conditionnalBa = $conditionnalBa;

        return $this;
    }

    public function getConditionalTara(): ?string
    {
        return $this->conditionalTara;
    }

    public function setConditionalTara(string $conditionalTara): static
    {
        $this->conditionalTara = $conditionalTara;

        return $this;
    }

    public function getImperative(): ?string
    {
        return $this->imperative;
    }

    public function setImperative(string $imperative): static
    {
        $this->imperative = $imperative;

        return $this;
    }

    public function getVolitionnal(): ?string
    {
        return $this->volitionnal;
    }

    public function setVolitionnal(string $volitionnal): static
    {
        $this->volitionnal = $volitionnal;

        return $this;
    }

    public function getCausative(): ?string
    {
        return $this->causative;
    }

    public function setCausative(string $causative): static
    {
        $this->causative = $causative;

        return $this;
    }

    public function getPotential(): ?string
    {
        return $this->potential;
    }

    public function setPotential(string $potential): static
    {
        $this->potential = $potential;

        return $this;
    }

    public function getTeForm(): ?string
    {
        return $this->teForm;
    }

    public function setTeForm(string $teForm): static
    {
        $this->teForm = $teForm;

        return $this;
    }

    public function getTaForm(): ?string
    {
        return $this->taForm;
    }

    public function setTaForm(string $taForm): static
    {
        $this->taForm = $taForm;

        return $this;
    }
}
