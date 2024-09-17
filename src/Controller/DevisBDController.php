<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\Devis1Type;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/devis/b/d')]
class DevisBDController extends AbstractController
{
    #[Route('/show', name: 'app_devis_b_d_index', methods: ['GET'])]
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('devis_bd/index.html.twig', [
            'devis' => $devisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_devis_b_d_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devis = new Devis();
        $form = $this->createForm(Devis1Type::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($devis);
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_b_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis_bd/new.html.twig', [
            'devi' => $devis,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_b_d_show', methods: ['GET'])]
    public function show(Devis $devi): Response
    {
        return $this->render('devis_bd/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_devis_b_d_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Devis1Type::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_devis_b_d_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devis_bd/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_devis_b_d_delete', methods: ['POST'])]
    public function delete(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_devis_b_d_index', [], Response::HTTP_SEE_OTHER);
    }
}
