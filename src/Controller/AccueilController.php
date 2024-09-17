<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accuiel', name: 'app_accuiel')]
    public function index(): Response
    {
        return $this->render('accuiel/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}
