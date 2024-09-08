<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CourseController extends AbstractController
{
    #[Route('/courses', name: 'course_index')]
    public function index(CourseRepository $courseRepository): Response
    {
        // On récupère tous les cours de la base de données
        $courses = $courseRepository->findAll();

        // On renvoie la vue qui affiche la liste des cours
        return $this->render('course/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/courses/{id}', name: 'course_show')]
    public function show(Course $course): Response
    {
        // On renvoie la vue qui affiche un seul cours avec ses détails
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/courses/new', name: 'course_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
    
        // Vérifier si l'utilisateur est bien connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour créer un cours.');
        }
    
        // Vérifier si l'utilisateur est bien une instance de User
        if (!$user instanceof User) {
            throw new \Exception('L\'utilisateur connecté est invalide.');
        }
    
        $course = new Course();
        $course->setTeacher($user);
    
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($course);
            $em->flush();
    
            return $this->redirectToRoute('course_index');
        }
    
        return $this->render('course/form.html.twig', [
            'form' => $form->createView(),
            'formTitle' => 'Créer un nouveau cours',
            'buttonLabel' => 'Créer',
        ]);
    }

    #[Route('/courses/{id}/edit', name: 'course_edit')]
    public function edit(Request $request, Course $course, EntityManagerInterface $em): Response
    {
        // On récupère le cours à modifier et on crée un formulaire basé sur CourseType
        $form = $this->createForm(CourseType::class, $course);

        // On gère la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est valide, on met à jour le cours en base de données
            $em->flush();

            // On redirige vers la page d'affichage du cours après modification
            return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
        }

        // Si le formulaire n'est pas soumis ou pas valide, on affiche le formulaire
        return $this->render('course/form.html.twig', [
            'form' => $form->createView(),
            'formTitle' => 'Modifier le cours',
            'buttonLabel' => 'Mettre à jour',
        ]);
    }

    #[Route('/courses/{id}/delete', name: 'course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, EntityManagerInterface $em): Response
    {
        // On vérifie que la demande de suppression a bien un token CSRF valide
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            // Si le token est valide, on supprime le cours de la base de données
            $em->remove($course);
            $em->flush();
        }

        // On redirige vers la liste des cours après suppression
        return $this->redirectToRoute('course_index');
    }
}
