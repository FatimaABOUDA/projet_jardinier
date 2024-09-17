<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\HaieType;
use App\Entity\Haie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\HaieRepository;

class ListHaieController extends AbstractController
{
    #[Route('/list/haie', name: 'app_list_haie')]
    public function index(Request $request, EntityManagerInterface $entityManager,HaieRepository $haieRepository): Response
    {
        $haies = new Haie();
        $form = $this->createForm(HaieType::class, $haies);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($haies);
            $entityManager->flush();
            return $this->redirectToRoute('app_list_haie');
        }

        //affichage du tableau des haies
        $haies = $haieRepository->findBy([], ['code' => 'DESC']);

        return $this->render('list_haie/index.html.twig', [
            'controller_name' => 'ListHaieController',
            'form' => $form->createView(),
            'haies' => $haies,
        ]);
    }
}
