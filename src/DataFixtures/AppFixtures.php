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
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

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
        for ($i = 0; $i < 20; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $deck = new Deck();
            $deck->setName("Deck " . $i);
            $deck->setPublic(mt_rand(0, 1));
            $deck->setReverse(mt_rand(0, 1));
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
            $flashcardKanji->setTranslation("Translation " . $i);
            $flashcardKanji->setFurigana("Furigana " . $i);
            $flashcardKanji->setExample("Example " . $i);
            $flashcardKanji->setKunyomi("Kunyomi " . $i);
            $flashcardKanji->setOnyomi("Onyomi " . $i);
            $flashcardKanji->setKanji("Kanji " . $i);
            $flashcardKanji->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardKanji->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardKanji->setDuplicate(1);
            $deck = $flashcardKanji->getDecks()->toArray()[array_rand($flashcardKanji->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardKanji->addUser($user);

            $manager->persist($flashcardKanji);
            $listflashcardKanji[] = $flashcardKanji;
        }

        // Création des flashcardGrammar
        $listflashcardGrammar = [];
        for ($i = 0; $i < 50; $i++) {
            $flashcardGrammar = new FlashcardGrammar();

            $flashcardGrammar->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardGrammar->setTranslation("Translation " . $i);
            $flashcardGrammar->setFurigana("Furigana " . $i);
            $flashcardGrammar->setExample("Example " . $i);
            $flashcardGrammar->setGrammarPoint("GrammarPoin " . $i);
            $flashcardGrammar->setGrammarRule("GrammarRule " . $i);
            $flashcardGrammar->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardGrammar->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardGrammar->setDuplicate(1);
            $deck = $flashcardGrammar->getDecks()->toArray()[array_rand($flashcardGrammar->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardGrammar->addUser($user);
            $manager->persist($flashcardGrammar);
            $listflashcardGrammar[] = $flashcardGrammar;
        }

        // Création des flashcardVocabulary
        $listflashcardVocabulary = [];
        for ($i = 0; $i < 50; $i++) {
            $flashcardVocabulary = new FlashcardVocabulary();

            $flashcardVocabulary->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardVocabulary->setTranslation("Translation " . $i);
            $flashcardVocabulary->setFurigana("Furigana " . $i);
            $flashcardVocabulary->setExample("Example " . $i);
            $flashcardVocabulary->setWord("Word " . $i);
            $flashcardVocabulary->setImage("Image " . $i);
            $flashcardVocabulary->setAudio("Audio " . $i);
            $flashcardVocabulary->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardVocabulary->addDeck($listDeck[array_rand($listDeck)]);
            $deck = $flashcardVocabulary->getDecks()->toArray()[array_rand($flashcardVocabulary->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardVocabulary->addUser($user);
            $flashcardVocabulary->setDuplicate(1);

            $manager->persist($flashcardVocabulary);
            $listflashcardVocabulary[] = $flashcardVocabulary;
        }

        // Création des flashcardConjugation
        $listflashcardConjugation = [];
        for ($i = 0; $i < 50; $i++) {
            $flashcardConjugation = new FlashcardConjugation();

            $flashcardConjugation->setCreatedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardConjugation->setTranslation("Translation " . $i);
            $flashcardConjugation->setFurigana("Furigana " . $i);
            $flashcardConjugation->setExample("Example " . $i);
            $flashcardConjugation->setPolite("Polite " . $i);
            $flashcardConjugation->setNegative("Negative " . $i);
            $flashcardConjugation->setConditionnalBa("ConditionnalBa " . $i);
            $flashcardConjugation->setConditionalTara("ConditionalTara " . $i);
            $flashcardConjugation->setImperative("Imperative " . $i);
            $flashcardConjugation->setVolitionnal("Volitionnal " . $i);
            $flashcardConjugation->setCausative("Causative " . $i);
            $flashcardConjugation->setPotential("Potential " . $i);
            $flashcardConjugation->setTeForm("TeForm " . $i);
            $flashcardConjugation->setTaForm("TaForm " . $i);
            $flashcardConjugation->addDeck($listDeck[array_rand($listDeck)]);
            $flashcardConjugation->addDeck($listDeck[array_rand($listDeck)]);
            $deck = $flashcardConjugation->getDecks()->toArray()[array_rand($flashcardConjugation->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardConjugation->addUser($user);
            $flashcardConjugation->setDuplicate(1);


            $manager->persist($flashcardConjugation);
            $listflashcardConjugation[] = $flashcardConjugation;
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
            $listflashcardConjugation,
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
            $review->setReviewNumber(mt_rand(0, 30));
            $review->setScore(mt_rand(0, 5));
            $review->setIntervalReview(mt_rand(0, 364) + (mt_rand(0, PHP_INT_MAX - 1) / PHP_INT_MAX));
            $review->setEaseFactor(mt_rand(130, 250) / 100);
            $manager->persist($review);
            $listReviews[] = $review;
        }


        for ($i = 0; $i < 25; $i++) {

            $flashcardModif = new FlashcardModification();
            $flashcard = $listFlascards[array_rand($listFlascards)];
            $flashcardModif->setFlashcard($flashcard);

            $flashcardModif->setTranslation("M Translation " . $i);
            $flashcardModif->setFurigana("M KaFurigananji " . $i);
            $flashcardModif->setExample("M Example " . $i);

            if($flashcard instanceof FlashcardKanji) {
                $flashcardModif->setKanji("M Kanji " . $i);
                $flashcardModif->setOnyomi("M Onyomi " . $i);
                $flashcardModif->setKunyomi("M Kunyomi " . $i);

            }
            if($flashcard instanceof FlashcardGrammar) {
                $flashcardModif->setGrammarPoint("M GrammarPoint " . $i);
                $flashcardModif->setGrammarRule("M GrammarRule " . $i);
            }
            if($flashcard instanceof FlashcardConjugation) {
                $flashcardModif->setPolite("M Polite " . $i);
                $flashcardModif->setNegative("M Negative " . $i);
                $flashcardModif->setConditionnalBa("M ConditionnalBa " . $i);
                $flashcardModif->setConditionnalTara("M ConditionnalTara " . $i);
                $flashcardModif->setImperative("M Imperative " . $i);
                $flashcardModif->setVolitional("M Volitional " . $i);
                $flashcardModif->setCausative("M Causative " . $i);
                $flashcardModif->setPotential("M Potential " . $i);
                $flashcardModif->setTaForm("M TaForm " . $i);
                $flashcardModif->setTeForm("M TeForm " . $i);
            }
            if($flashcard instanceof FlashcardVocabulary) {
                $flashcardModif->setWord("M Word " . $i);
                $flashcardModif->setAudio("M Audio " . $i);
                $flashcardModif->setImage("M Image " . $i);

            }
            $deck = $flashcard->getDecks()->toArray()[array_rand($flashcard->getDecks()->toArray())];
            $user = $deck->getUser();
            $flashcardModif->setDeck($deck);
            $flashcardModif->setUser($user);

            $manager->persist($flashcardModif);
            $listFlashcardModifications[] = $flashcardModif;
        }

        $manager->flush();
    }
}
