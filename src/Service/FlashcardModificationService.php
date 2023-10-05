<?php

namespace App\Service;

use App\Entity\Deck;
use App\Entity\Flashcard;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardModification;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use App\Repository\FlashcardModificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class FlashcardModificationService
{
    public function __construct(
        private SerializerService $serializerService,
        private FlashcardModificationRepository $flashcardModificationRepository,
        private EntityManagerInterface $em,
    ) {}

    /**
     * Enregistre en bdd les modifications d'une carte pour un paquet et un utilisateur précis
     *
     * @param Flashcard $flashcard la carte à modifier
     * @param Deck $deck le paquet de la carte
     * @param array $data les modifications
     * @return FlashcardModification
     */
    public function setFlashcardModificationData(
        Flashcard $flashcard,
        Deck $deck,
        array $data,
        User $user,
    ): FlashcardModification {

        //on vérifie si les mofication pour cette carte et ce paquet existe déjà
        $flashcardModif = $this->flashcardModificationRepository->findOneBy(
            ['deck' => $deck->getId(), 'flashcard' => $flashcard->getId()]
        );
        $new = false;

        //sinon on crée un nouvel objet modification
        if(!$flashcardModif) {
            $flashcardModif = new FlashcardModification();
            $flashcardModif->setDeck($deck);
            $flashcardModif->setUser($user);
            $flashcardModif->setFlashcard($flashcard);
            $new = true;
        }

        //On enregistre les modifcations communes à toutes les cartes
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

        //On enregistre les modifcations spécifiques à chaque type de cartes
        switch (true) {
            case $flashcard instanceof FlashcardGrammar:
                $this->setModifGrammar($data, $flashcardModif);
                break;
            case $flashcard instanceof FlashcardKanji:
                $this->setModifKanji($data, $flashcardModif);
                break;
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

    /**
     * Applique les modifications d'une carte en fonction du paquet auxquelle elle appartient
     *
     * @param array $flashcard la carte d'origine
     * @param Deck $deck le paquet de la carte
     * @return array la carte modifiée
     */
    public function getFlashcardModification(array $flashcard, Deck $deck): array
    {
        //on vérifie si il existe des modifications pour cette carte et ce paquet
        $flashcardModif = $this->flashcardModificationRepository->findOneBy(
            ['deck' => $deck->getId(), 'flashcard' => $flashcard['id']]
        );
        //si oui on remplace les valeurs de la carte par celle des modifications
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
     * Enregistre en bdd les modifications des cartes de grammaire
     *
     * @param array $data
     * @param FlashcardModification $flashcardModif
     * @return void
     */
    private function setModifGrammar(array $data, FlashcardModification $flashcardModif)
    {
        if (isset($data['construction'])) {
            $flashcardModif->setConstruction($data['construction']);
        }
        if (isset($data['grammarnotes'])) {
            $flashcardModif->setGrammarnotes($data['grammarnotes']);
        }
    }

    /**
     * Enregistre en bdd les modifications des cartes de kanji
     *
     * @param array $data
     * @param FlashcardModification $flashcardModif
     * @return void
     */
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

    /**
     * Enregistre en bdd les modifications des cartes de vocabulaire

     *
     * @param array $data
     * @param FlashcardModification $flashcardModif
     * @return void
     */
    private function setModifVocabulary(array $data, FlashcardModification $flashcardModif)
    {
        if (isset($data['synonym'])) {
            $flashcardModif->setSynonym($data['synonym']);
        }
        if (isset($data['antonym'])) {
            $flashcardModif->setAntonym($data['antonym']);
        }
    }

}
