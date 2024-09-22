<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Course;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    #[Route('/courses/{course_id}/lessons', name: 'lesson_index')]
    public function index(LessonRepository $lessonRepository, EntityManagerInterface $em, int $course_id): Response
    {
        // Récupère le cours en fonction de l'ID
        $course = $em->getRepository(Course::class)->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas.');
        }

        // Récupère les leçons associées à ce cours
        $lessons = $lessonRepository->findBy(['course' => $course]);

        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
            'course' => $course, // Passe l'objet course à la vue
        ]);
    }


    #[Route('/courses/{course_id}/lessons/new', name: 'lesson_new')]
    public function new(Request $request, EntityManagerInterface $em, int $course_id): Response
    {
        // Récupérer le cours depuis la base de données
        $course = $em->getRepository(Course::class)->find($course_id);
        
        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas.');
        }
    
        // Créer une nouvelle leçon et l'associer au cours
        $lesson = new Lesson();
        $lesson->setCourse($course);
    
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lesson);
            $em->flush();
    
            return $this->redirectToRoute('lesson_index', ['course_id' => $course->getId()]);
        }
    
        return $this->render('lesson/form.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
            'course' => $course, // Envoyer le cours pour afficher le titre, par exemple
        ]);
    }
    



    #[Route('/courses/{course_id}/lessons/{id}', name: 'lesson_show')]
    public function show(Lesson $lesson): Response
    {
        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'course' => $lesson->getCourse(), // Passer l'objet course au template
        ]);
    }

    #[Route('/courses/{course_id}/lessons/{id}/edit', name: 'lesson_edit')]
    public function edit(Request $request, Lesson $lesson, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('lesson_index', ['course_id' => $lesson->getCourse()->getId()]);
        }

        return $this->render('lesson/form.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView(),
            'course' => $lesson->getCourse(),  // Ajout du cours
        ]);
    }


    #[Route('/courses/{course_id}/lessons/{id}/delete', name: 'lesson_delete', methods: ['POST'])]
    public function delete(Request $request, Lesson $lesson, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lesson->getId(), $request->request->get('_token'))) {
            $em->remove($lesson);
            $em->flush();
        }

        return $this->redirectToRoute('lesson_index', ['course_id' => $lesson->getCourse()->getId()]);
    }
}
