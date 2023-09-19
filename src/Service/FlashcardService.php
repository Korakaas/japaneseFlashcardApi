<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardConjugation;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use App\Repository\FlashcardModificationRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\FlashcardRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class FlashcardService
{
    private $flashcardRepository;
    private $em;
    private $flashcardModificationRepository;
    private $reviewRepository;


    public function __construct(
        FlashcardRepository $flashcardRepository,
        EntityManagerInterface $em,
        FlashcardModificationRepository $flashcardModificationRepository,
        ReviewRepository $reviewRepository,
    ) {
        $this->flashcardRepository = $flashcardRepository;
        $this->em = $em;
        $this->flashcardModificationRepository = $flashcardModificationRepository;
        $this->reviewRepository = $reviewRepository;

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
        $result = $this->flashcardRepository->findByToReview($deckId, $userId, $today);
        // dd($result);
        if (!$result['totalCardCount']) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                'Pas de carte à réviser aujourd\'hui'
            );
        }
        return $result;
    }

    public function updateFlashcardProperties(Flashcard $flashcardToUpdate, array $data)
    {
        // Update common properties for all types of flashcards
        if (isset($data['front'])) {
            $flashcardToUpdate->setFront($data['front']);
        }
        if (isset($data['back'])) {
            $flashcardToUpdate->setFront($data['back']);
        }
        if (isset($data['furigana'])) {
            $flashcardToUpdate->setFurigana($data['furigana']);
        }
        if (isset($data['example'])) {
            $flashcardToUpdate->setExample($data['example']);
        }

        // Update type-specific properties
        switch (true) {
            case $flashcardToUpdate instanceof FlashcardGrammar:
                $this->setModifGrammar($data, $flashcardToUpdate);
                break;
            case $flashcardToUpdate instanceof FlashcardKanji:
                $this->setModifKanji($data, $flashcardToUpdate);

                break;
                // case $flashcardToUpdate instanceof FlashcardConjugation:
                //     $this->setModifConjugation($data, $flashcardToUpdate);

                break;
            case $flashcardToUpdate instanceof FlashcardVocabulary:
                $this->setModifVocabulary($data, $flashcardToUpdate);

                break;
            default:
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Requête invalide');
        }
    }

    public function deleteFlashcard(Flashcard $flashcardToDelete, User $user, Deck $deck)
    {
        $flashcardUsers = $flashcardToDelete->getUser();

        // Si la carte appartient a plusieurs utilisateurs on ne supprime pas la carte
        // On supprime les modifications de l'user
        // on la retire du deck de l'utilisateur
        // On supprime l'utilisateur de la carte
        if($flashcardToDelete->isDuplicate() && count($flashcardUsers) > 1) {
            $flashcardModif = $this->flashcardModificationRepository->findOneBy(
                ['deck' => $deck->getid(), 'flashcard' => $flashcardToDelete->getId()]
            );
            if($flashcardModif) {
                $this->em->remove($flashcardModif);
            }

            $deck->removeFlashcard($flashcardToDelete);
            $flashcardToDelete->removeUser($user);
            if(count($flashcardUsers) === 1) {
                $flashcardToDelete->setDuplicate(false);
            }
        } else {
            //sinon on supprime simplement la carte
            $this->em->remove($flashcardToDelete);
        }

        //Dans tous les cas on supprime les review associées à la carte et à l'utilisateur
        $review = $this->reviewRepository->findOneBy(
            ['user' => $user->getId(), 'flashcard' => $flashcardToDelete->getId()]
        );
        if($review) {
            $this->em->remove($review);
        }

        $this->em->flush();
    }

    /**
     * Détermine le type de la carte
     *
     * @param [type] $flashcardToReturn
     * @return string
     */
    public function getFlashcardType($flashcardToReturn): string
    {
        if ($flashcardToReturn instanceof FlashcardKanji) {
            return 'kanji';
        } elseif ($flashcardToReturn instanceof FlashcardGrammar) {
            return 'grammar';
        } elseif ($flashcardToReturn instanceof FlashcardVocabulary) {
            return 'vocabulary';
        }
        //  elseif ($flashcardToReturn instanceof FlashcardConjugation) {
        //     return 'conjugation';
        // }

        return 'unknown';
    }

    private function setModifGrammar(array $data, FlashcardGrammar $flashcard)
    {
        if (isset($data['grammarConstruction'])) {
            $flashcard->setConstruction($data['grammarConstruction']);
        }
        if (isset($data['grammarNotes'])) {
            $flashcard->setGrammarnotes($data['grammarNotes']);
        }
    }

    private function setModifKanji(array $data, FlashcardKanji $flashcard)
    {
        if (isset($data['onyomi'])) {
            $flashcard->setOnyomi($data['onyomi']);
        }
        if (isset($data['kunyomi'])) {
            $flashcard->setKunyomi($data['kunyomi']);
        }
        if (isset($data['mnemotic'])) {
            $flashcard->setMnemonic($data['mnemotic']);
        }
    }

    // private function setModifConjugation(array $data, FlashcardConjugation $flashcard)
    // {
    //     if (isset($data['polite'])) {
    //         $flashcard->setPolite($data['polite']);
    //     }
    //     if (isset($data['negative'])) {
    //         $flashcard->setNegative($data['negative']);
    //     }
    //     if (isset($data['causative'])) {
    //         $flashcard->setCausative($data['causative']);
    //     }
    // }

    private function setModifVocabulary(array $data, FlashcardVocabulary $flashcard)
    {
        // if (isset($data['image'])) {
        //     $flashcard->setImage($data['image']);
        // }
        // if (isset($data['audio'])) {
        //     $flashcard->setAudio($data['audio']);
        // }
        if (isset($data['synonym'])) {
            $flashcard->setSynonym($data['synonym']);
        }
        if (isset($data['antonym'])) {
            $flashcard->setAntonym($data['antonym']);
        }
    }
}
