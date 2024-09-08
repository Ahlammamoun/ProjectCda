<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuizzeController extends AbstractController
{
    #[Route('/quizze', name: 'app_quizze')]
    public function index(): Response
    {
        return $this->render('quizze/index.html.twig', [
            'controller_name' => 'QuizzeController',
        ]);
    }
}
