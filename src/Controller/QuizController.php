<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Form\QuizAnswerType;
use App\Entity\Course;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\PersistentCollection;

class QuizController extends AbstractController
{

    #[Route('/courses/{course_id}/quizzes/{id}', name: 'quiz_show')]
    public function show(int $course_id, int $id, EntityManagerInterface $em): Response
    {
        // Récupérer le cours en fonction de course_id
        $course = $em->getRepository(Course::class)->find($course_id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours spécifié n\'existe pas.');
        }

        // Récupérer le quiz en fonction de l'id
        $quiz = $em->getRepository(Quiz::class)->find($id);
        if (!$quiz) {
            throw $this->createNotFoundException('Le quiz spécifié n\'existe pas.');
        }

        // Rendre le template pour afficher les détails du quiz
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
            'course' => $course,
        ]);
    }

    #[Route('/courses/{course_id}/quizzes', name: 'quiz_index')]
    public function index(EntityManagerInterface $em, int $course_id): Response
    {
        // Récupérer le cours en fonction de l'ID
        $course = $em->getRepository(Course::class)->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas.');
        }

        // Récupérer tous les quiz associés à ce cours
        $quizzes = $em->getRepository(Quiz::class)->findBy(['course' => $course]);

        // Rendre la vue et passer les données
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
            'course' => $course,
        ]);
    }



    #[Route('/courses/{course_id}/quizzes/new', name: 'quiz_new')]
    public function new(Request $request, EntityManagerInterface $em, int $course_id): Response
    {
        // Récupérer le cours en fonction de l'ID passé dans l'URL
        $course = $em->getRepository(Course::class)->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Le cours spécifié n\'existe pas.');
        }

        // Créer un nouveau quiz et l'associer au cours
        $quiz = new Quiz();
        $quiz->setCourse($course);

        // Créer le formulaire
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Générer automatiquement le titre du quiz en fonction du cours
            $quiz->setTitle('Quiz pour ' . $course->getTitle());

            // Enregistrer le quiz
            $em->persist($quiz);
            $em->flush();

            return $this->redirectToRoute('quiz_index', ['course_id' => $course->getId()]);
        }

        // Rendre le formulaire et passer la variable 'quiz'
        return $this->render('quiz/form.html.twig', [
            'form' => $form->createView(),
            'quiz' => $quiz,
            'course' => $course,  // Passer le cours à la vue si besoin
        ]);
    }


    #[Route('/courses/{course_id}/quizzes/{id}/answer', name: 'quiz_answer')]
public function answer(int $course_id, int $id, Request $request, EntityManagerInterface $em): Response
{
    // Récupérer le quiz
    $quiz = $em->getRepository(Quiz::class)->find($id);

    if (!$quiz) {
        throw $this->createNotFoundException('Le quiz spécifié n\'existe pas.');
    }

    // Récupérer les questions associées au quiz
    $questions = $quiz->getQuizQuestions();

    if ($questions->isEmpty()) {
        throw $this->createNotFoundException('Aucune question trouvée pour ce quiz.');
    }

    // Créer le formulaire avec les questions
    $form = $this->createForm(QuizAnswerType::class, null, [
        'questions' => $questions->toArray(),  // Convertir en tableau pour le formulaire
    ]);

    $form->handleRequest($request);

    // Initialiser les réponses comme tableau vide
    $answers = [];

    if ($form->isSubmitted() && $form->isValid()) {
        foreach ($questions as $index => $question) {
            $userAnswer = $form->get('answer_' . $index)->getData();
            $correctAnswer = $question->getCorrectAnswer();

            $answers[] = [
                'question' => $question->getQuestion(),
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctAnswer,
                'isCorrect' => ($userAnswer === $correctAnswer),
            ];
        }

        // Affichez un message de succès
        $this->addFlash('success', 'Vos réponses ont été soumises avec succès.');
    }

    return $this->render('quiz/quiz_answer.html.twig', [
        'quiz' => $quiz,
        'questions' => $questions,
        'form' => $form->createView(), // Assurez-vous que la variable form est correctement passée
        'answers' => $answers, // Passer les réponses à la vue
    ]);
}

    
    

    #[Route('/courses/{course_id}/quizzes/{id}/result', name: 'quiz_result')]
    public function result(int $course_id, int $id, Request $request, EntityManagerInterface $em): Response
    {
        $quiz = $em->getRepository(Quiz::class)->find($id);
    
        if (!$quiz) {
            throw $this->createNotFoundException('Le quiz spécifié n\'existe pas.');
        }
    
        // Récupérer les questions
        $questions = $quiz->getQuizQuestions();
    
        // Créer un tableau pour stocker les réponses
        $answers = [];
    
        // Vérifier si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Récupérer les réponses de l'utilisateur
            foreach ($questions as $index => $question) {
                $answerKey = 'answer_' . $index; // La clé que vous avez définie dans le formulaire
                $userAnswer = $request->request->get($answerKey);
                $answers[] = [
                    'question' => $question->getQuestion(),
                    'userAnswer' => $userAnswer,
                    'correctAnswer' => $question->getCorrectAnswer(),
                    'isCorrect' => ($userAnswer === $question->getCorrectAnswer()),
                ];
            }
    
            // Ajouter un message flash de succès ou d'erreur ici si nécessaire
            // $this->addFlash('success', 'Vos réponses ont été soumises avec succès!');
        }
    
        return $this->render('quiz/quiz_result.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
            'answers' => $answers, // Passer les réponses à la vue
        ]);
    }
    
    #[Route('/courses/{course_id}/quizzes/{id}/reset', name: 'quiz_reset')]
    public function reset(int $course_id, int $id, EntityManagerInterface $em): Response
    {
        // Vous pouvez rediriger l'utilisateur vers le formulaire initial ou créer un nouveau formulaire ici
        return $this->redirectToRoute('quiz_answer', ['course_id' => $course_id, 'id' => $id]);
    }
    
}
