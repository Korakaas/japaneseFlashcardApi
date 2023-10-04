<?php

namespace App\Service;

use App\Entity\Flashcard;
use App\Entity\Review;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReviewService
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Permet d'ajuster le facteur de facilité et l'interval avant la
     * prochaine révision selon le score de l'utilisateur
     *
     * @param integer|null $score  réponse de l'utilisateur, de 1 à 5
     * 1 et 2  = une mauvaise réponse
     * 3 = bonne réponse mais difficile à se remémorer
     * 4 et 5 = bonne réponses, facile à se remémorer
     * @param Flashcard|null $flashcardReviewed
     * @param Review|null $review
     * @param User $user
     * @return void
     */
    public function updateReview(
        ?int $score,
        ?Flashcard $flashcardReviewed,
        ?Review $review,
        User $user
    ): void {
        $min = 1;
        $max = 5;
        //on vérifie que le score n'est pas nul et est dans la plage valide
        if($score !== 0 && $score >= $min && $score <= $max) {
            // Initialiser les valeurs par défaut
            $knowLevel = 0; //niveau de connaissance de la carte
            $easeFactor = 2.5; //facteur de simplicité compris en 1.3 et 2.5
            $interval = 0.0; //interval entre deux révision en jour
            // si pas de review => première fois que l'utilisateur révise la carte
            if(!$review) {
                $review = new Review();
                $review->setUser($user);
                $review->setFlashcard($flashcardReviewed);
            } // on récupère les données de la précédente révision sinon
            else {
                $knowLevel = $review->getKnownLevel();
                $easeFactor = $review->getEaseFactor();
                $interval = $review->getIntervalReview();
            }

            //Ajustement du facteur de simplicité et l'interval de révision en fonction du score
            if($knowLevel < 3) {
                //on est encore en phrase d'apprentissage
                if($score < 3) {
                    //réponse incorrecte, on définit la prochaine révision 30 min après
                    $knowLevel = 0;
                    $interval = 30 * 1.0 / (24.0 * 60.0);
                } else {

                    $knowLevel ++;

                    //on définie l'interval en fonction du niveau de connaissance de la carte
                    if($knowLevel === 1) {
                        $interval = 30.0 * 1.0 / (24.0 * 60.0);

                    } elseif($knowLevel === 2) {
                        $interval = 0.5;
                    } else {
                        $interval = 1;
                    }
                }

                // Ajoute une marge aléatoire de 10 % à l'interval de review pour
                // éviter un regroupement excessif des révisions
                $interval = $this->setFuzz($interval);

            } else {
                // on est en phase de révision
                if ($score < 3) {
                    //réponse incorrecte, on définit la prochaine révision 30 min après
                    $knowLevel = 0;
                    $interval = 30 * 1.0 / (24.0 * 60.0);
                    // Réduction du facteur de facilité
                    $easeFactor = max(1.3, $easeFactor - 0.20);
                } else {
                    //réponse correcte, on ajuste le facteur de facilité et l'interval
                    $knowLevel ++;

                    $intervalAdjustment = 1.0;

                    if ($score < 4) {
                        //la carte est jugé difficile donc on réduit l'interval
                        $intervalAdjustment = 0.8;
                    }

                    $easeFactor = max(
                        1.3,
                        $easeFactor + (0.1 - (5 - $score) * (0.08 + (5 - $score) * 0.02))
                    );

                    $interval = ceil($interval * $intervalAdjustment * $easeFactor);

                    // Ajoute une marge aléatoire de 10 % à l'interval de review pour
                    // éviter un regroupement excessif des révisions
                    $interval = $this->setFuzz($interval);
                }
            }

            // Mise à jour des valeurs de révision
            $review->setKnownLevel($knowLevel);
            $review->setIntervalReview($interval);
            $review->setEaseFactor($easeFactor);
            $review->setScore($score);
            $review->setReviewedAt(new DateTimeImmutable());

            // Enregistrement des modifications en base de données
            $this->em->persist($review);
            $this->em->flush();
        } else {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'La requête est incorrecte');
        }
    }


    private function setFuzz(float $interval): float
    {
        $interval = $interval * (1.0 + (mt_rand() / mt_getrandmax()) * 0.05);
        return $interval;

    }

}
