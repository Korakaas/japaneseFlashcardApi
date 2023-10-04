<?php

namespace App\DataFixtures;

use App\Entity\DailyStats;
use App\Entity\Deck;
use App\Entity\FlashcardModification;
use App\Entity\FlashcardConjugation;
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

        // Création de users "normaux"
        $listUser = [];
        for ($i = 0; $i < 20; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $user = new User();
            $user->setEmail("user" . $i . "@jpflashcardapi.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $user->setPseudo('User' . $i);
            $user->setRegisteredAt(new DateTimeImmutable("$year-$month-$day"));
            $manager->persist($user);
            $listUser[] = $user;
        }

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@jpflashcardapi.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $userAdmin->setPseudo('Admin');
        $userAdmin->setRegisteredAt(new DateTimeImmutable('2023-08-15'));
        $manager->persist($userAdmin);


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
            $flashcardKanji->setMnemonic("Mnemotic " . $i);
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
                $flashcardKanjiBack->setMnemonic("Kanji " . $i);
                $flashcardKanjiBack->addUser($user);
                $flashcardKanjiBack->addDeck($deck);
                $flashcardKanjiBack->isDuplicate($flashcardKanji->isDuplicate());
                $flashcardKanjiBack->setFlashcard($flashcardKanji);
                $flashcardKanjiBack->setReverse(1);
                $flashcardKanji->setFlashcard($flashcardKanjiBack);
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
                $flashcardGrammarBack->setFlashcard($flashcardGrammar);
                $flashcardGrammar->setFlashcard($flashcardGrammarBack);
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
            $flashcardVocabulary->setImage("Image " . $i);
            $flashcardVocabulary->setAudio("Audio " . $i);
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
                $flashcardVocabularyBack->setImage("Image " . $i);
                $flashcardVocabularyBack->setAudio("Audio " . $i);
                $flashcardVocabularyBack->addUser($user);
                $flashcardVocabularyBack->addDeck($deck);
                $flashcardVocabularyBack->setSide('back');
                $flashcardVocabularyBack->isDuplicate($flashcardVocabulary->isDuplicate());
                $flashcardVocabularyBack->setFlashcard($flashcardVocabulary);
                $flashcardVocabularyBack->setReverse(1);
                $flashcardVocabulary->setFlashcard($flashcardVocabularyBack);
                $listflashcardVocabulary[] = $flashcardVocabularyBack;
                $manager->persist($flashcardVocabularyBack);

            }
            $manager->persist($flashcardVocabulary);
            $listflashcardVocabulary[] = $flashcardVocabulary;
        }

        // Création des flashcardConjugation
        // $listflashcardConjugation = [];
        // for ($i = 0; $i < 50; $i++) {
        //     $flashcardConjugation = new FlashcardConjugation();

        //     $flashcardConjugation->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
        //     $flashcardConjugation->setDictionnary("Dictionnary " . $i);
        //     $flashcardConjugation->setTranslation("Translation " . $i);
        //     $flashcardConjugation->setFurigana("Furigana " . $i);
        //     $flashcardConjugation->setExample("Example " . $i);
        //     $flashcardConjugation->setPolite("Polite " . $i);
        //     $flashcardConjugation->setNegative("Negative " . $i);
        //     $flashcardConjugation->setConditionnalBa("ConditionnalBa " . $i);
        //     $flashcardConjugation->setConditionnalTara("ConditionnalTara " . $i);
        //     $flashcardConjugation->setImperative("Imperative " . $i);
        //     $flashcardConjugation->setVolitional("Volitional " . $i);
        //     $flashcardConjugation->setCausative("Causative " . $i);
        //     $flashcardConjugation->setPotential("Potential " . $i);
        //     $flashcardConjugation->setTeForm("TeForm " . $i);
        //     $flashcardConjugation->setTaForm("TaForm " . $i);
        //     $flashcardConjugation->addDeck($listDeck[array_rand($listDeck)]);
        //     $flashcardConjugation->addDeck($listDeck[array_rand($listDeck)]);
        //     $deck = $flashcardConjugation->getDecks()->toArray()[array_rand($flashcardConjugation->getDecks()->toArray())];
        //     $user = $deck->getUser();
        //     $flashcardConjugation->addUser($user);
        //     $flashcardConjugation->setDuplicate(mt_rand(0, 1));
        //     $flashcardConjugation->setReverse(mt_rand(0, 1));
        //     if($flashcardConjugation->isReverse()) {

        //         $flashcardConjugation->setSide('front');
        //         $flashcardConjugationBack = new FlashcardConjugation();
        //         $flashcardConjugationBack->setCreatedAt($flashcardConjugation->getCreatedAt());
        //         $flashcardConjugationBack->setDictionnary("Dictionnary " . $i);
        //         $flashcardConjugationBack->setTranslation("Translation " . $i);
        //         $flashcardConjugationBack->setFurigana("Furigana " . $i);
        //         $flashcardConjugationBack->setExample("Example " . $i);
        //         $flashcardConjugationBack->setPolite("Word " . $i);
        //         $flashcardConjugationBack->setNegative("Image " . $i);
        //         $flashcardConjugationBack->setConditionnalBa("ConditionnalBa " . $i);
        //         $flashcardConjugationBack->setConditionnalTara("ConditionnalTara " . $i);
        //         $flashcardConjugationBack->setImperative("Imperative " . $i);
        //         $flashcardConjugationBack->setVolitional("Volitional " . $i);
        //         $flashcardConjugationBack->setCausative("Causative " . $i);
        //         $flashcardConjugationBack->setPotential("Potential " . $i);
        //         $flashcardConjugationBack->setTeForm("TeForm " . $i);
        //         $flashcardConjugationBack->setTaForm("TaForm " . $i);
        //         $flashcardConjugationBack->addUser($user);
        //         $flashcardConjugationBack->addDeck($deck);
        //         $flashcardConjugationBack->setSide('back');
        //         $flashcardConjugationBack->isDuplicate($flashcardConjugation->isDuplicate());
        //         $flashcardConjugationBack->setFlashcard($flashcardConjugation);
        //         $flashcardConjugationBack->setReverse(1);
        //         $flashcardConjugation->setFlashcard($flashcardConjugationBack);
        //         $listflashcardConjugation[] = $flashcardConjugationBack;
        //         $manager->persist($flashcardConjugationBack);
        //     }


        //     $manager->persist($flashcardConjugation);
        //     $listflashcardConjugation[] = $flashcardConjugation;
        // }

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
            // $listflashcardConjugation,
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
                // if($flashcard instanceof FlashcardConjugation) {
                //     $flashcardModif->setPolite("M Polite " . $i);
                //     $flashcardModif->setNegative("M Negative " . $i);
                //     $flashcardModif->setConditionnalBa("M ConditionnalBa " . $i);
                //     $flashcardModif->setConditionnalTara("M ConditionnalTara " . $i);
                //     $flashcardModif->setImperative("M Imperative " . $i);
                //     $flashcardModif->setVolitional("M Volitional " . $i);
                //     $flashcardModif->setCausative("M Causative " . $i);
                //     $flashcardModif->setPotential("M Potential " . $i);
                //     $flashcardModif->setTaForm("M TaForm " . $i);
                //     $flashcardModif->setTeForm("M TeForm " . $i);
                // }
                if($flashcard instanceof FlashcardVocabulary) {
                    $flashcardModif->setAudio("M Audio " . $i);
                    $flashcardModif->setImage("M Image " . $i);

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
