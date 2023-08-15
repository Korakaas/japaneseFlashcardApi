<?php

namespace App\Entity;

use App\Repository\FlashcardConjugationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashcardConjugationRepository::class)]
class FlashcardConjugation extends Flashcard
{

    #[ORM\Column(length: 30)]
    private ?string $polite = null;

    #[ORM\Column(length: 30)]
    private ?string $negative = null;

    #[ORM\Column(length: 30)]
    private ?string $conditionnalBa = null;

    #[ORM\Column(length: 30)]
    private ?string $conditionalTara = null;

    #[ORM\Column(length: 30)]
    private ?string $imperative = null;

    #[ORM\Column(length: 30)]
    private ?string $volitionnal = null;

    #[ORM\Column(length: 30)]
    private ?string $causative = null;

    #[ORM\Column(length: 30)]
    private ?string $potential = null;

    #[ORM\Column(length: 30)]
    private ?string $teForm = null;

    #[ORM\Column(length: 30)]
    private ?string $taForm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

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
