<?php

namespace App\DataFixtures;

use App\Entity\DailyStats;
use App\Entity\Deck;
use App\Entity\FlashcardModification;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardVocabulary;
use App\Entity\Review;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}

    public function load(ObjectManager $manager): void
    {

        // Création de users
        $listUser = [];
        for ($i = 0; $i < 20; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $user = new User();
            $user->setEmail("user" . $i . "@jpflashcardapi.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "Password1*"));
            $user->setPseudo('User' . $i);
            $user->setRegisteredAt(new DateTimeImmutable("$year-$month-$day"));
            $manager->persist($user);
            $listUser[] = $user;
        }

        // Création des decks
        $listDeck = [];
        for ($i = 0; $i < 50; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $deck = new Deck();
            $deck->setName("Deck " . $i);
            $deck->setPublic(mt_rand(0, 1));
            $deck->setUser($listUser[array_rand($listUser)]);
            $deck->setDescription("Description" . $i);
            $deck->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $manager->persist($deck);
            $listDeck[] = $deck;
        }

        // Création des flashcardKanji
        $listflashcardKanji = [];
        for ($i = 0; $i < 50; $i++) {
            $flashcardKanji = new FlashcardKanji();

            $flashcardKanji->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardKanji->setFront("Front " . $i);
            $flashcardKanji->setBack("Back " . $i);
            $flashcardKanji->setFurigana("Furigana " . $i);
            $flashcardKanji->setExample("Example " . $i);
            $flashcardKanji->setKunyomi("Kunyomi " . $i);
            $flashcardKanji->setOnyomi("Onyomi " . $i);
            $flashcardKanji->setMnemotic("Mnemotic " . $i);
            $flashcardKanji->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardKanji->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardKanji->setDuplicate(mt_rand(0, 1));
            $flashcardKanji->setReverse(mt_rand(0, 1));
            $deck = $flashcardKanji->getDecks()->toArray()[array_rand($flashcardKanji->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardKanji->addUser($user);
            if($flashcardKanji->isReverse()) {

                $flashcardKanjiBack = new FlashcardKanji();
                $flashcardKanjiBack->setCreatedAt($flashcardKanji->getCreatedAt());
                $flashcardKanjiBack->setFront($flashcardKanji->getBack());
                $flashcardKanjiBack->setBack($flashcardKanji->getBack());
                $flashcardKanjiBack->setExample("Example " . $i);
                $flashcardKanjiBack->setFurigana("Furigana " . $i);
                $flashcardKanjiBack->setKunyomi("Kunyomi " . $i);
                $flashcardKanjiBack->setOnyomi("Onyomi " . $i);
                $flashcardKanjiBack->setMnemotic("Kanji " . $i);
                $flashcardKanjiBack->addUser($user);
                $flashcardKanjiBack->addDeck($deck);
                $flashcardKanjiBack->isDuplicate($flashcardKanji->isDuplicate());
                $flashcardKanjiBack->setReverse(1);
                $listflashcardKanji[] = $flashcardKanjiBack;
                $manager->persist($flashcardKanjiBack);
            }

            $manager->persist($flashcardKanji);
            $listflashcardKanji[] = $flashcardKanji;
        }

        // Création des flashcardGrammar
        $listflashcardGrammar = [];
        for ($i = 0; $i < 50; $i++) {
            $flashcardGrammar = new FlashcardGrammar();

            $flashcardGrammar->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardGrammar->setFront("front " . $i);
            $flashcardGrammar->setBack("back " . $i);
            $flashcardGrammar->setFurigana("Furigana " . $i);
            $flashcardGrammar->setExample("Example " . $i);
            $flashcardGrammar->setConstruction("GrammarConstruction " . $i);
            $flashcardGrammar->setGrammarnotes("GrammarNotes " . $i);
            $flashcardGrammar->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardGrammar->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardGrammar->setDuplicate(mt_rand(0, 1));
            $flashcardGrammar->setReverse(mt_rand(0, 1));
            $deck = $flashcardGrammar->getDecks()->toArray()[array_rand($flashcardGrammar->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardGrammar->addUser($user);
            if($flashcardGrammar->isReverse()) {

                $flashcardGrammar->setSide('front');
                $flashcardGrammarBack = new FlashcardGrammar();
                $flashcardGrammarBack->setCreatedAt($flashcardGrammar->getCreatedAt());
                $flashcardGrammarBack->setFront($flashcardGrammar->getBack());
                $flashcardGrammarBack->setBack($flashcardGrammar->getFront());
                $flashcardGrammarBack->setFurigana("Furigana " . $i);
                $flashcardGrammarBack->setExample("Example " . $i);
                $flashcardGrammarBack->setConstruction("GrammarConstruction " . $i);
                $flashcardGrammarBack->setGrammarnotes("GrammarNotes " . $i);
                $flashcardGrammarBack->addUser($user);
                $flashcardGrammarBack->addDeck($deck);
                $flashcardGrammarBack->setSide('back');
                $flashcardGrammarBack->isDuplicate($flashcardGrammar->isDuplicate());
                $flashcardGrammarBack->setReverse(1);
                $listflashcardGrammar[] = $flashcardGrammarBack;
                $manager->persist($flashcardGrammarBack);

            }
            $manager->persist($flashcardGrammar);
            $listflashcardGrammar[] = $flashcardGrammar;
        }

        // Création des flashcardVocabulary
        $listflashcardVocabulary = [];
        for ($i = 0; $i < 50; $i++) {
            $flashcardVocabulary = new FlashcardVocabulary();

            $flashcardVocabulary->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardVocabulary->setFront("Front " . $i);
            $flashcardVocabulary->setBack("Back " . $i);
            $flashcardVocabulary->setFurigana("Furigana " . $i);
            $flashcardVocabulary->setExample("Example " . $i);
            $flashcardVocabulary->setSynonym("Synonym " . $i);
            $flashcardVocabulary->setAntonym("Antonym " . $i);
            $flashcardVocabulary->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardVocabulary->addDeck($listDeck[array_rand($listDeck)]);
            $deck = $flashcardVocabulary->getDecks()->toArray()[array_rand($flashcardVocabulary->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardVocabulary->addUser($user);
            $flashcardVocabulary->setDuplicate(mt_rand(0, 1));
            $flashcardVocabulary->setReverse(mt_rand(0, 1));
            if($flashcardVocabulary->isReverse()) {

                $flashcardVocabulary->setSide('front');
                $flashcardVocabularyBack = new FlashcardVocabulary();
                $flashcardVocabularyBack->setCreatedAt($flashcardVocabulary->getCreatedAt());
                $flashcardVocabularyBack->setFront($flashcardVocabulary->getFront());
                $flashcardVocabularyBack->setBack($flashcardVocabulary->getBack());
                $flashcardVocabularyBack->setFurigana("Furigana " . $i);
                $flashcardVocabularyBack->setExample("Example " . $i);
                $flashcardVocabularyBack->setSynonym("Synonym " . $i);
                $flashcardVocabularyBack->setAntonym("Antonym " . $i);
                $flashcardVocabularyBack->addUser($user);
                $flashcardVocabularyBack->addDeck($deck);
                $flashcardVocabularyBack->setSide('back');
                $flashcardVocabularyBack->isDuplicate($flashcardVocabulary->isDuplicate());
                $flashcardVocabularyBack->setReverse(1);
                $listflashcardVocabulary[] = $flashcardVocabularyBack;
                $manager->persist($flashcardVocabularyBack);

            }
            $manager->persist($flashcardVocabulary);
            $listflashcardVocabulary[] = $flashcardVocabulary;
        }

        // Création des dailyStats
        $listdailyStats = [];
        for ($i = 0; $i < 50; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $nbReview = rand(1, 100);
            $dailyStats = new DailyStats();
            $dailyStats->setDate(new DateTimeImmutable("$year-$month-$day"));
            $dailyStats->setFlashcardsReviewed($nbReview);
            $dailyStats->setCorrectAnswers(mt_rand(0, $nbReview));
            $dailyStats->setDeck($listDeck[array_rand($listDeck)]);
            $manager->persist($dailyStats);
            $listdailyStats[] = $dailyStats;
        }

        //liste de toutes les flashcards
        $listFlascards = array_merge(
            $listflashcardGrammar,
            $listflashcardVocabulary,
            $listflashcardKanji
        );

        // Création des reviews
        $listReviews = [];
        for ($i = 0; $i < 100; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);

            $review = new Review();

            $flashcard = $listFlascards[array_rand($listFlascards)];
            $review->setFlashcard($flashcard);
            $deck = $flashcard->getDecks()->toArray()[array_rand($flashcard->getDecks()->toArray())];
            $user = $deck->getUser();
            $review->setUser($user);
            $review->setReviewedAt(new DateTimeImmutable("$year-$month-$day"));
            $review->setKnownLevel(mt_rand(0, 30));
            $review->setScore(mt_rand(0, 5));
            $review->setIntervalReview(mt_rand(0, 364) + (mt_rand(0, PHP_INT_MAX - 1) / PHP_INT_MAX));
            $review->setEaseFactor(mt_rand(130, 250) / 100);
            $manager->persist($review);
            $listReviews[] = $review;
        }

        // Création des modifications

        for ($i = 0; $i < 100; $i++) {
            $flashcard = $listFlascards[array_rand($listFlascards)];
            if($flashcard->isduplicate()) {
                $flashcardModif = new FlashcardModification();
                $flashcardModif->setFlashcard($flashcard);

                $flashcardModif->setFront("M Front " . $i);
                $flashcardModif->setBack("M Back " . $i);
                $flashcardModif->setFurigana("M KaFurigananji " . $i);
                $flashcardModif->setExample("M Example " . $i);

                if($flashcard instanceof FlashcardKanji) {
                    $flashcardModif->setMnemotic("M Mnemotic " . $i);
                    $flashcardModif->setOnyomi("M Onyomi " . $i);
                    $flashcardModif->setKunyomi("M Kunyomi " . $i);

                }
                if($flashcard instanceof FlashcardGrammar) {
                    $flashcardModif->setGrammarnotes("M GrammarNotes " . $i);
                    $flashcardModif->setConstruction("M construction " . $i);
                }
                if($flashcard instanceof FlashcardVocabulary) {
                    $flashcardModif->setAntonym("M Antonym " . $i);
                    $flashcardModif->setSynonym("M Synonym " . $i);
                }
                $deck = $flashcard->getDecks()->toArray()[array_rand($flashcard->getDecks()->toArray())];
                $user = $deck->getUser();
                $flashcardModif->setDeck($deck);
                $flashcardModif->setUser($user);
                if($flashcard->getUser() !== $user) {
                    $flashcard->addUser($user);
                }

                $manager->persist($flashcardModif);
                $listFlashcardModifications[] = $flashcardModif;
            }

        }

        $manager->flush();
    }
}
