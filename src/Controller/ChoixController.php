<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Devis;


class ChoixController extends AbstractController
{
    #[Route('/choix', name: 'app_choix')]
    public function index(): Response
    {
        return $this->render('choix/index.html.twig', [
            'controller_name' => 'ChoixController',
        ]);
    }

    #[Route('/choix/modifier/{id}', name: 'app_choix_modifier')]
    public function choixModifier(Request $request, $id): Response
    {
        // Récupérer l'entité Devis correspondant à l'ID
        $devis = $this->getDoctrine()->getRepository(Devis::class)->find($id);

        // Vérifier si le devis existe
        if (!$devis) {
            throw $this->createNotFoundException('Aucun devis trouvé pour l\'ID '.$id);
        }

        // Maintenant vous pouvez utiliser $devis dans votre vue

        return $this->render('choix/choixModifier.html.twig', [
            'controller_name' => 'ChoixController',
            'devis' => $devis,
        ]);
    }
}
