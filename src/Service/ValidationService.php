<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardModification;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    public function __construct(private ValidatorInterface $validator) {}

    /**
     * Valide les données d'un paquet
     *
     * @param Deck $deck
     * @param ValidatorInterface $validator
     * @throws HttpException si les données sont invalides
     * @return void
     */
    public function validateDeck(Deck $deck): void
    {
        $errors = $this->validator->validate($deck, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                json_encode($errorsMessage, JSON_UNESCAPED_UNICODE)
            );
        }
    }

    /**
     * Valide les données d'une carte
     *
     * @param Flashcard $flashcard
     * @param ValidatorInterface $validator
     * @throws HttpException si les données sont invalides
     * @return void
     */
    public function validateFlashcard(Flashcard $flashcard)
    {
        $errors = $this->validator->validate($flashcard, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                json_encode($errorsMessage, JSON_UNESCAPED_UNICODE)
            );
        }
    }

    /**
    * Valide les données des modifications de carte
    *
    * @param Flashcard $flashcard
    * @param ValidatorInterface $validator
    * @throws HttpException si les données sont invalides
    * @return void
    */
    public function validateFlashcardModification(FlashcardModification $flashcardModif)
    {
        $errors = $this->validator->validate($flashcardModif, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                json_encode($errorsMessage, JSON_UNESCAPED_UNICODE)
            );
        }
    }

    /**
     * Valide les données d'un utilisateur
     *
     * @param User $user
     * @param ValidatorInterface $validator
     * @throws HttpException si les données sont invalides
     * @return void
     */
    public function validateUser(User $user)
    {
        $errors = $this->validator->validate($user, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                json_encode($errorsMessage, JSON_UNESCAPED_UNICODE)
            );
        }
    }
}
