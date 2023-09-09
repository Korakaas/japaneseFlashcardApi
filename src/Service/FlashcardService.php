<?php

namespace App\Service;

use App\Entity\Flashcard;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\FlashcardRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DateTime;

class FlashcardService
{
    private $flashcardRepository;

    public function __construct(FlashcardRepository $flashcardRepository)
    {
        $this->flashcardRepository = $flashcardRepository;
    }

    /**
     * Retourne une carte en fonction de son Id et du deck
     *
     * @param int $flashcardId
     * @param int $deck
     * @throws HttpException si la carte n'est pas trouvée
     * @return Flashcard
     */
    public function findFlashcardByIdAndDeck(int $deckId, int $flashcardId): Flashcard
    {
        $flashcard = $this->flashcardRepository->findOneByIdAndDeck($deckId, $flashcardId);
        if (!$flashcard) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                'Cette carte n\'appartient pas à ce deck ou n\'existe pas'
            );
        }
        return $flashcard;
    }

    /**
     * Retourne une carte en fonction de son Id et du deck
     *
     * @param int $flashcardId
     * @param int $deck
     * @throws HttpException si la carte n'est pas trouvée
     * @return array
     */
    public function findFlashcardToreview(int $deckId, int $userId): array
    {
        $today = new DateTime(date('Y-m-d'));
        $flashcard = $this->flashcardRepository->findByToReview($deckId, $userId, $today);
        if (!$flashcard) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                'Pas de carte à réviser aujourd\'hui'
            );
        }
        // dd($flashcard);
        return $flashcard;
    }

    /**
     * Valide les données d'une carte
     *
     * @param Flashcard $flashcard
     * @param ValidatorInterface $validator
     * @throws HttpException si les données sont invalides
     * @return void
     */
    public function validateFlashcard(Flashcard $flashcard, ValidatorInterface $validator)
    {
        $errors = $validator->validate($flashcard, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errorsMessage));
        }
    }
}
