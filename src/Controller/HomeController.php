<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController  extends AbstractController
{
    #[Route('/')]
    public function number(): Response
    {
        $number = (rand(0, 100));
        return $this->render('base.html.twig', [


            'number' => $number,
        ]);
    }

    #[Route('/test')]
    public function test(): Response
    {
        $number = (rand(0, 100));
        return $this->render('base.html.twig', [


            'number' => $number,
        ]);
    }



}
