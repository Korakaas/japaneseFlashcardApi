<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use App\Repository\FlashcardModificationRepository;
use App\Repository\FlashcardRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class FlashcardService
{
    public function __construct(
        private FlashcardRepository $flashcardRepository,
        private EntityManagerInterface $em,
        private FlashcardModificationRepository $flashcardModificationRepository,
        private ReviewRepository $reviewRepository,
    ) {}

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
     * Retourne 20 cartes qui doivent être révisées
     *
     * @param int $flashcardId
     * @param int $deck
     * @throws HttpException si aucune carte n'est trouvée
     * @return array les cartes
     */
    public function findFlashcardToreview(int $deckId, int $userId): array
    {
        $today = new DateTime(date('Y-m-d'));
        $result = $this->flashcardRepository->findByToReview($deckId, $userId, $today);
        if (!$result['totalCardCount']) {
            throw new HttpException(
                Response::HTTP_OK,
                'Pas de carte à réviser aujourd\'hui'
            );
        }
        return $result;
    }

    /**
     * Met à jour une carte
     *
     * @param Flashcard $flashcardToUpdate
     * @param array $data les modifications
     * @return void
     */
    public function updateFlashcardProperties(Flashcard $flashcardToUpdate, array $data)
    {
        // Enregistre les propriétés communes à toutes les cartes
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

        // Enregistre les propriétés spécifiques à chaque carte
        switch (true) {
            case $flashcardToUpdate instanceof FlashcardGrammar:
                $this->setModifGrammar($data, $flashcardToUpdate);
                break;
            case $flashcardToUpdate instanceof FlashcardKanji:
                $this->setModifKanji($data, $flashcardToUpdate);
                break;
            case $flashcardToUpdate instanceof FlashcardVocabulary:
                $this->setModifVocabulary($data, $flashcardToUpdate);
                break;
            default:
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Requête invalide');
        }
    }

    /**
     * Supprime une carte
     *
     * @param Flashcard $flashcardToDelete
     * @param User $user
     * @param Deck $deck
     * @return void
     */
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
     * @param Flashcard $flashcardToReturn
     * @return string le type  de la carte
     */
    public function getFlashcardType(Flashcard $flashcardToReturn): string
    {
        if ($flashcardToReturn instanceof FlashcardKanji) {
            return 'kanji';
        } elseif ($flashcardToReturn instanceof FlashcardGrammar) {
            return 'grammar';
        } elseif ($flashcardToReturn instanceof FlashcardVocabulary) {
            return 'vocabulary';
        }

        return 'unknown';
    }

    /**
     * Enregistre les modifications des cartes de type Grammaire
     *
     * @param array $data
     * @param FlashcardGrammar $flashcard
     * @return void
     */
    private function setModifGrammar(array $data, FlashcardGrammar $flashcard)
    {
        if (isset($data['construction'])) {
            $flashcard->setConstruction($data['construction']);
        }
        if (isset($data['grammarnotes'])) {
            $flashcard->setGrammarnotes($data['grammarnotes']);
        }
    }

    /**
     * Enregistre les modifications des cartes de type Kanji
     *
     * @param array $data
     * @param FlashcardKanji $flashcard
     * @return void
     */
    private function setModifKanji(array $data, FlashcardKanji $flashcard)
    {
        if (isset($data['onyomi'])) {
            $flashcard->setOnyomi($data['onyomi']);
        }
        if (isset($data['kunyomi'])) {
            $flashcard->setKunyomi($data['kunyomi']);
        }
        if (isset($data['mnemotic'])) {
            $flashcard->setMnemotic($data['mnemotic']);
        }
    }

    /**
     * Enregistre les modifications des cartes de type Vocabulaire
     *
     * @param array $data
     * @param FlashcardVocabulary $flashcard
     * @return void
     */
    private function setModifVocabulary(array $data, FlashcardVocabulary $flashcard)
    {
        if (isset($data['synonym'])) {
            $flashcard->setSynonym($data['synonym']);
        }
        if (isset($data['antonym'])) {
            $flashcard->setAntonym($data['antonym']);
        }
    }
}
