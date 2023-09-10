<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessService
{
    /**
     * Vérifie si l'utilisateur existe
     *
     * @param User $user
     * @throws HttpException si l'utilisateur n'existe pas
     * @return void
     */
    public function handleNoUser(User $user): void
    {
        if (!$user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Vous n\'avez pas accès à cette page');
        }
    }

    /**
    * Vérifie si l'utilisateur a accès au deck, sinon lance une exception HTTP.
    *
    * @param Deck $deck
    * @param User $user
    * @throws HttpException
    */
    public function checkDeckAccess(Deck $deck, User $user): void
    {
        if ($deck->getUser() !== $user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }
    }

    /**
    * Vérifie que le paquet est bien public.
    *
    * @param Deck $deck
    * @throws HttpException
    */
    public function checkDeckPublic(Deck $deck): void
    {
        if (!$deck->isPublic()) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'L\'utilisateur n\'a pas accès au deck');
        }
    }
}
