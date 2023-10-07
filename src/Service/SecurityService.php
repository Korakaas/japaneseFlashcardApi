<?php

namespace App\Service;

use App\Entity\Flashcard;
use App\Entity\Review;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\Length;

class SecurityService
{
    public function checkPasswordStrength(string $password)
    {
        if(strlen($password) < 8) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                'Le mot de passe doit contenir au moins 8 caractères'
            );
        }

        if (!preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[A-Z]/', $password)) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                'Le mot de passe doit contenir au moins 1 caractère spécial, 1 majuscule et 1 chiffre'
            );
        }
    }
}
