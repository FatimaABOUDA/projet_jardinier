<?php

namespace App\Controller;

use App\Repository\HaieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Haie;



class MesureController extends AbstractController
{
    #[Route('/mesure', name: 'app_mesure')]
    public function index(Request $request, SessionInterface $session,  HaieRepository $haieRepository): Response
    {
        //la liste des haies
        $haies = $haieRepository->findAll();

        // Récupérer le type d'utilisateur à partir des données du formulaire
        $typeUtilisateur = $request->request->get('typeUtilisateur');

        // Créer une variable de session pour stocker le type d'utilisateur
        $session->set('typeUtilisateur', $typeUtilisateur);

        return $this->render('mesure/index.html.twig', [
            'controller_name' => 'MesureController',
            'haies' => $haies,
            'typeUtilisateur' => $typeUtilisateur,

        ]);
    }
}
