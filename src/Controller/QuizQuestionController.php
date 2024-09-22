<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizQuestion;
use App\Form\QuizQuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizQuestionController extends AbstractController
{
    #[Route('/quiz/{quiz_id}/questions', name: 'question_index')]
    public function index(EntityManagerInterface $em, int $quiz_id): Response
    {
        $quiz = $em->getRepository(Quiz::class)->find($quiz_id);

        if (!$quiz) {
            throw $this->createNotFoundException('Quiz non trouvé.');
        }

        return $this->render('question/index.html.twig', [
            'quiz' => $quiz,
            'questions' => $quiz->getQuizQuestions(),
        ]);
    }

    #[Route('/quiz/{quiz_id}/questions/new', name: 'question_new')]
    public function new(Request $request, EntityManagerInterface $em, int $quiz_id): Response
    {
        $quiz = $em->getRepository(Quiz::class)->find($quiz_id);

        if (!$quiz) {
            throw $this->createNotFoundException('Quiz non trouvé.');
        }

        $question = new QuizQuestion();
        $question->setQuiz($quiz);

        $form = $this->createForm(QuizQuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('question_index', ['quiz_id' => $quiz->getId()]);
        }

        return $this->render('question/form.html.twig', [
            'form' => $form->createView(),
            'quiz' => $quiz,
        ]);
    }

    #[Route('/quiz/{quiz_id}/questions/{id}/edit', name: 'question_edit')]
    public function edit(Request $request, QuizQuestion $question, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuizQuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('question_index', ['quiz_id' => $question->getQuiz()->getId()]);
        }

        return $this->render('question/form.html.twig', [
            'form' => $form->createView(),
            'quiz' => $question->getQuiz(),
        ]);
    }

    #[Route('/quiz/{quiz_id}/questions/{id}/delete', name: 'question_delete', methods: ['POST'])]
    public function delete(Request $request, QuizQuestion $question, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('question_index', ['quiz_id' => $question->getQuiz()->getId()]);
    }
}
