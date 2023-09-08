<?php

namespace App\Service;

use App\Entity\Flashcard;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Entity\FlashcardConjugation;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardVocabulary;

class FlashcardModificationService
{
    public function getFlashcardModificationData(Flashcard $flashcard, array $data)
    {
        $modif = [];
        if (isset($data['translation'])) {
            $modif['translation'] = $data['translation'];
        }
        if (isset($data['furigana'])) {
            $modif['furigana'] = $data['furigana'];
        }
        if (isset($data['example'])) {
            $modif['example'] = $data['example'];
        }

        switch (true) {
            case $flashcard instanceof FlashcardGrammar:
                if (isset($data['grammarPoint'])) {
                    $modif['grammarPoint'] = $data['grammarPoint'];
                }
                if (isset($data['grammarRule'])) {
                    $modif['grammarRule'] = $data['grammarRule'];
                }
                break;
            case $flashcard instanceof FlashcardKanji:
                if (isset($data['onyomi'])) {
                    $modif['onyomi'] = $data['onyomi'];
                }
                if (isset($data['kunyomi'])) {
                    $modif['kunyomi'] = $data['kunyomi'];
                }
                if (isset($data['kanji'])) {
                    $modif['kanji'] = $data['kanji'];
                }
                break;
            case $flashcard instanceof FlashcardConjugation:
                if (isset($data['polite'])) {
                    $modif['polite'] = $data['polite'];
                }
                if (isset($data['negative'])) {
                    $modif['negative'] = $data['negative'];
                }
                if (isset($data['causative'])) {
                    $modif['causative'] = $data['causative'];
                }
                break;
            case $flashcard instanceof FlashcardVocabulary:
                if (isset($data['word'])) {
                    $modif['word'] = $data['word'];
                }
                if (isset($data['image'])) {
                    $modif['image'] = $data['image'];
                }
                if (isset($data['audio'])) {
                    $modif['audio'] = $data['audio'];
                }
                break;
            default:
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Requête invalide');
        }

        return $modif;
    }
}
