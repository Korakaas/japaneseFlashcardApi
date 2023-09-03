<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function handleNoUser(User $user): void
    {
        if (!$user) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Vous n\'avez pas accès à cette page');
        }
    }
}
