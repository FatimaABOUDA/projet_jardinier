<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\HaieType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HaieRepository;

class ModifierHaieController extends AbstractController
{
    #[Route('/modifier_haie/{code}', name: 'modifier_haie')]
    public function index(Request $request, EntityManagerInterface $entityManager, HaieRepository $haieRepository, $code): Response
    {
        //Récupérer la haie à partir du code
        $haie = $haieRepository->findOneBy(['code' => $code]);

        if (!$haie) {
            throw $this->createNotFoundException('La haie avec le code ' . $code . ' n\'existe pas.');
        }

        // Créer le formulaire de modification
        $form = $this->createForm(HaieType::class, $haie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer la modification dans la base de données
            $entityManager->flush();

            // Rediriger vers la liste des haies après la modification
            return $this->redirectToRoute('app_list_haie');
        }

        return $this->render('modifier_haie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
