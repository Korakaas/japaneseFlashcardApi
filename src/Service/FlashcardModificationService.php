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
    private $serializerService;
    private $flashcardModificationRepository;
    private $em;

    public function __construct(
        SerializerService $serializerService,
        FlashcardModificationRepository $flashcardModificationRepository,
        EntityManagerInterface $em,
    ) {
        $this->serializerService = $serializerService;
        $this->flashcardModificationRepository = $flashcardModificationRepository;
        $this->em = $em;
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

    private function setModifGrammar(array $data, FlashcardModification $flashcardModif)
    {
        if (isset($data['construction'])) {
            $flashcardModif->setConstruction($data['construction']);
        }
        if (isset($data['grammarnotes'])) {
            $flashcardModif->setGrammarnotes($data['grammarnotes']);
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
