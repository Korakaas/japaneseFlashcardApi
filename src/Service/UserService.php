<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
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
}
