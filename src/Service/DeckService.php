<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeckService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Valide les données d'un paquet
     *
     * @param Deck $deck
     * @param ValidatorInterface $validator
     * @throws HttpException si les données sont invalides
     * @return void
     */
    public function validateDeck(Deck $deck)
    {
        $errors = $this->validator->validate($deck, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errorsMessage, JSON_UNESCAPED_UNICODE));
        }
    }

    /**
    * Vérifie si le deck à dupliquer n'appartient pas déjà à l'utilisateur,
    * sinon lance une exception HTTP.
    *
    * @param Deck $deck
    * @param User $user
    * @throws HttpException
    */
    public function checkDeckToDuplicateUser(Deck $deck, User $user): void
    {
        if ($deck->getUser() === $user) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Vous ne pouvez pas dupliquez vos propres paquet');
        }
    }
}
