<?php

namespace App\DataFixtures;

use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer un quiz
        $quiz = new Quiz();
        $quiz->setTitle('Quiz de culture générale');

        // Créer des questions pour ce quiz
        $questions = [
            ['question' => 'Quelle est la capitale de la France ?', 'answer' => 'Paris'],
            ['question' => 'Qui a peint la Joconde ?', 'answer' => 'Léonard de Vinci'],
            ['question' => 'En quelle année a eu lieu la révolution française ?', 'answer' => '1789']
        ];

        foreach ($questions as $q) {
            // Vérifiez bien que vous définissez une question non nulle
            $quizQuestion = new QuizQuestion();
            $quizQuestion->setQuestion($q['question']);  // Assigner une question valide
            $quizQuestion->setCorrectAnswer($q['answer']);
            $quizQuestion->setQuiz($quiz);  // Lier la question au quiz

            $manager->persist($quizQuestion); // Sauvegarder la question
        }

        $manager->persist($quiz);  // Sauvegarder le quiz
        $manager->flush();  // Appliquer les changements
    }
}
