<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\FlashcardConjugation;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardModification;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use App\Repository\FlashcardModificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FlashcardModificationService
{
    private $serializerService;
    private $flashcardModificationRepository;
    private $em;
    private $validator;

    public function __construct(
        SerializerService $serializerService,
        FlashcardModificationRepository $flashcardModificationRepository,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        $this->serializerService = $serializerService;
        $this->flashcardModificationRepository = $flashcardModificationRepository;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * Undocumented function
     *
     * @param Flashcard $flashcard
     * @param Deck $deck
     * @param array $data
     * @return FlashcardModification
     */
    public function setFlashcardModificationData(
        Flashcard $flashcard,
        Deck $deck,
        array $data,
        User $user,
    ): FlashcardModification {
        $flashcardModif = $this->flashcardModificationRepository->findOneBy(
            ['deck' => $deck->getId(), 'flashcard' => $flashcard->getId()]
        );
        $new = false;
        if(!$flashcardModif) {
            $flashcardModif = new FlashcardModification();
            $flashcardModif->setDeck($deck);
            $flashcardModif->setUser($user);
            $flashcardModif->setFlashcard($flashcard);
            $new = true;
        }

        if (isset($data['front'])) {
            $flashcardModif->setFront($data['front']);
        }
        if (isset($data['back'])) {
            $flashcardModif->setBack($data['back']);
        }
        if (isset($data['furigana'])) {
            $flashcardModif->setFurigana($data['furigana']);
        }
        if (isset($data['example'])) {
            $flashcardModif->setExample($data['example']);
        }

        switch (true) {
            case $flashcard instanceof FlashcardGrammar:
                $this->setModifGrammar($data, $flashcardModif);
                break;
            case $flashcard instanceof FlashcardKanji:
                $this->setModifKanji($data, $flashcardModif);
                break;
                // case $flashcard instanceof FlashcardConjugation:
                //     $this->setModifConjugation($data, $flashcardModif);
                //     break;
            case $flashcard instanceof FlashcardVocabulary:
                $this->setModifVocabulary($data, $flashcardModif);
                break;
            default:
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Requête invalide');
        }

        if($new) {
            $this->em->persist($flashcardModif);
        }

        return $flashcardModif;
    }

    public function getFlashcardModification(array $flashcard, Deck $deck): array
    {
        $flashcardModif = $this->flashcardModificationRepository->findOneBy(
            ['deck' => $deck->getId(), 'flashcard' => $flashcard['id']]
        );
        if($flashcardModif) {
            $flashcardModif = $this->serializerService->serializeFlashcardModification(
                $flashcardModif,
                'getFlashcardModif'
            );
            $flashcardModif = json_decode($flashcardModif, true);
            foreach($flashcardModif as $key => $value) {

                if($value && array_key_exists($key, $flashcard)) {
                    $flashcard[$key] = $value;
                }
            }
        }

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
    public function validateFlashcardModification(FlashcardModification $flashcardModif)
    {
        $errors = $this->validator->validate($flashcardModif, null);

        if (count($errors) > 0) {
            $errorsMessage = [];
            foreach ($errors as $error) {
                $errorsMessage[] = $error->getMessage();
            }
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($errorsMessage));
        }
    }

    private function setModifGrammar(array $data, FlashcardModification $flashcardModif)
    {
        if (isset($data['grammarConstruction'])) {
            $flashcardModif->setConstruction($data['grammarConstruction']);
        }
        if (isset($data['grammarNotes'])) {
            $flashcardModif->setGrammarnotes($data['grammarNotes']);
        }
    }

    private function setModifKanji(array $data, FlashcardModification $flashcardModif)
    {
        if (isset($data['onyomi'])) {
            $flashcardModif->setOnyomi($data['onyomi']);
        }
        if (isset($data['kunyomi'])) {
            $flashcardModif->setKunyomi($data['kunyomi']);
        }
        if (isset($data['mnemotic'])) {
            $flashcardModif->setMnemotic($data['mnemotic']);
        }
    }

    // private function setModifConjugation(array $data, FlashcardModification $flashcardModif)
    // {
    //     if (isset($data['polite'])) {
    //         $flashcardModif->setPolite($data['polite']);
    //     }
    //     if (isset($data['negative'])) {
    //         $flashcardModif->setNegative($data['negative']);
    //     }
    //     if (isset($data['causative'])) {
    //         $flashcardModif->setCausative($data['causative']);
    //     }
    // }

    private function setModifVocabulary(array $data, FlashcardModification $flashcardModif)
    {
        if (isset($data['image'])) {
            $flashcardModif->setImage($data['image']);
        }
        if (isset($data['audio'])) {
            $flashcardModif->setAudio($data['audio']);
        }
    }

}
