<?php

namespace App\Controller;

use App\Entity\Haie;
use App\Repository\HaieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\HaieType;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class CreerHaieController extends AbstractController
{

    #[Route('/creerhaie', name: 'app_creerhaie')]
    public function index(Request $request, EntityManagerInterface $entityManager, HaieRepository $haieRepository): Response
    {


        $haie = new Haie();
        $form = $this->createForm(HaieType::class, $haie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($haie);
            $entityManager->flush();

            return $this->redirectToRoute('app_creerhaie');
        }

        $haies = $haieRepository->findAll();

        return $this->render('creer_haie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
