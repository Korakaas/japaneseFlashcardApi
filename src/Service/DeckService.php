<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeckService
{
    public function __construct(private ValidatorInterface $validator) {}

    /**
    * Vérifie si le deck à dupliquer n'appartient pas déjà à l'utilisateur,
    * sinon lance une exception HTTP.
    *
    * @param Deck $deck
    * @param User $user
    * @throws HttpException si le paquet appartient à l'utilisateur
    */
    public function checkDeckToDuplicateUser(Deck $deck, User $user): void
    {
        if ($deck->getUser() === $user) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                'Vous ne pouvez pas dupliquez vos propres paquets'
            );
        }
    }
}
