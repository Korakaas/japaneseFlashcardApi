<?php

namespace App\DataFixtures;

use App\Entity\DailyStats;
use App\Entity\Deck;
use App\Entity\Flashcard;
use App\Entity\FlashcardConjugation;
use App\Entity\FlashcardGrammar;
use App\Entity\FlashcardKanji;
use App\Entity\FlashcardVocabulary;
use App\Entity\User;
use DateTime;
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
            $deck = new Deck();
            $deck->setName("Deck " . $i);
            $deck->setPublic(rand(0, 1));
            $deck->setReverse(rand(0, 1));
            $deck->addUser($listUser[array_rand($listUser)]);
            $deck->addUser($listUser[array_rand($listUser)]);
            $manager->persist($deck);
            $listDeck[] = $deck;
        }

        // Création des flashcardKanji
        $listflashcardKanji = [];
        for ($i = 0; $i < 50; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $flashcardKanji = new FlashcardKanji();
            $flashcardKanji->setTranslation("Translation " . $i);
            $flashcardKanji->setFurigana("Furigana " . $i);
            $flashcardKanji->setExample("Example " . $i);
            $flashcardKanji->setKunyomi("Kunyomi " . $i);
            $flashcardKanji->setOnyomi("Onyomi " . $i);
            $flashcardKanji->setDeck($listDeck[array_rand($listDeck)]);
            $flashcardKanji->setReviewedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardKanji->setReviewNumber(rand(0,30));
            $flashcardKanji->setScore(rand(0,5));
            $flashcardKanji->setReviewInterval(rand(0,365));
            $manager->persist($flashcardKanji);
            $listflashcardKanji[] = $flashcardKanji;
        }

        // Création des flashcardGrammar
        $listflashcardGrammar = [];
        for ($i = 0; $i < 50; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $flashcardGrammar = new FlashcardGrammar();
            $flashcardGrammar->setTranslation("Translation " . $i);
            $flashcardGrammar->setFurigana("Furigana " . $i);
            $flashcardGrammar->setExample("Example " . $i);
            $flashcardGrammar->setGrammarPoint("GrammarPoin " . $i);
            $flashcardGrammar->setGrammarRule("GrammarRule " . $i);
            $flashcardGrammar->setDeck($listDeck[array_rand($listDeck)]);
            $flashcardGrammar->setReviewedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardGrammar->setReviewNumber(rand(0,30));
            $flashcardGrammar->setScore(rand(0,5));
            $flashcardGrammar->setReviewInterval(rand(0,365));
            $manager->persist($flashcardGrammar);
            $listflashcardGrammar[] = $flashcardGrammar;
        }

        // Création des flashcardVocabulary
        $listflashcardVocabulary = [];
        for ($i = 0; $i < 50; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $flashcardVocabulary = new FlashcardVocabulary();
            $flashcardVocabulary->setTranslation("Translation " . $i);
            $flashcardVocabulary->setFurigana("Furigana " . $i);
            $flashcardVocabulary->setExample("Example " . $i);
            $flashcardVocabulary->setWord("Word " . $i);
            $flashcardVocabulary->setImage("Image " . $i);
            $flashcardVocabulary->setAudio("Audio " . $i);
            $flashcardVocabulary->setDeck($listDeck[array_rand($listDeck)]);
            $flashcardVocabulary->setReviewedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardVocabulary->setReviewNumber(rand(0,30));
            $flashcardVocabulary->setScore(rand(0,5));
            $flashcardVocabulary->setReviewInterval(rand(0,365));
            $manager->persist($flashcardVocabulary);
            $listflashcardVocabulary[] = $flashcardVocabulary;
        }

        // Création des flashcardConjugatio
        $listflashcardConjugation = [];
        for ($i = 0; $i < 50; $i++) {
            $year = mt_rand(2000, 2023);
            $month = mt_rand(1, 12);
            $day = mt_rand(1, 28);
            $flashcardConjugation = new FlashcardConjugation();
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
            $flashcardConjugation->setReviewedAt(new DateTimeImmutable("$year-$month-$day"));
            $flashcardConjugation->setDeck($listDeck[array_rand($listDeck)]);
            $flashcardConjugation->setReviewNumber(rand(0,30));
            $flashcardConjugation->setScore(rand(0,5));
            $flashcardConjugation->setReviewInterval(rand(0,365));
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
            $dailyStats->setDailyReviewNumber($nbReview);
            $dailyStats->setCorrectAnswerNumber(rand(0, $nbReview));
            $dailyStats->setDeck($listDeck[array_rand($listDeck)]);
            $dailyStats->setUser($listUser[array_rand($listUser)]);
            $manager->persist($dailyStats);
            $listdailyStats[] = $dailyStats;
        }

        $manager->flush();
    }
}
