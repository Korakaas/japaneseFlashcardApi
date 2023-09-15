<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private $serializer;
    private $em;
    private $validationService;
    private $passwordHasher;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidationService $validationService,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->validationService = $validationService;
        $this->passwordHasher = $passwordHasher;

    }

    /**
     * Permet d'enregistre un nouvel utilisateur
     *
     * @param Request $request
     * @throws \HttpException si
     *  -les données du formulaire sont incorrectes
     * @return JsonResponse
     */
    #[Route('/api/register', name: "createUser", methods: ['POST'])]
    public function createDeck(
        Request $request,
    ): JsonResponse {


        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        //validation des données
        $this->validationService->validateUser($user);
        $user->setRoles(['ROLE_USER']);
        $password = $user->getpassword();
        $encryptedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($encryptedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $user = $user->toArray();

        return $this->json(
            'Le compte a bien été crée',
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json;charset=UTF-8']
        );

    }
}
