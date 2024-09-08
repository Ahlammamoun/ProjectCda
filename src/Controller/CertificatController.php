<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CertificatController extends AbstractController
{
    #[Route('/certificat', name: 'app_certificat')]
    public function index(): Response
    {
        return $this->render('certificat/index.html.twig', [
            'controller_name' => 'CertificatController',
        ]);
    }
}
