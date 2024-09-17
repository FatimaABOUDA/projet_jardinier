<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\HaieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Haie;
use Doctrine\ORM\EntityManagerInterface;



class HaieController extends AbstractController
{
    #[Route('/haie', name: 'app_haie')]
    public function index(): Response
    {
        return $this->render('haie/index.html.twig', [
            'controller_name' => 'HaieController',
        ]);
    }
    #[Route('/haie/creer', name: 'app_haie_creer')]
    public function haie_creer(EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $categorie -> setLibelle('Persistant');


        $haie = new Haie();
        $haie->setCode('LA');
        $haie->setNom('Laurier');
        $haie->setPrix(30);

        $haie->setCategorie($categorie);

        $entityManager->persist($categorie);
        $entityManager->persist($haie);

        $entityManager->flush();
        return new Response('Type de haie créé : '.$haie->getNom().' à '.$haie->getPrix(). '€'
            . ' de catégorie : '.$haie->getCategorie()->getLibelle());


    }
    #[Route('/haie/{code}', name: 'app_haie_voir')]
    public function haie_voir(EntityManagerInterface $entityManager, string $code,HaieRepository $haieRepository): Response
    {
        $haie= $haieRepository->find($code);
        if (!$haie) {
            return new Response('Ce type de haie n\'existe pas : '.$code);
        }
        elseif ($code) {
//            return new Response('Type de haie : '.$haie->getNom().' à '.$haie->getPrix(). '€');
            return $this->render('haie/index.html.twig', array('maHaie' => $haie));
        }
        else{
            $mesHaies = $haieRepository->findAll();
            return $this->render('haie/index.html.twig', array('mesHaies' => $mesHaies));
        }
    }
    #[Route('/haie/modifier/{code}', name: 'app_haie_modifier')]
    public function haie_modifier(EntityManagerInterface $entityManager, string $code,HaieRepository $haieRepository): Response
    {
        $haie= $haieRepository->find($code);
        if (!$haie) {
            return new Response('Ce type de haie n\'existe pas : '.$code);
        }
        else {
            $haie->setPrix(42);
            $entityManager->flush();
            return new Response('Type de haie modifié : '.$haie->getNom().' à '.$haie->getPrix(). '€');
        }

    }
    //On supprime un objet avec la méthode remove() de l’entity manager :
    #[Route('/haie/supprimer/{code}', name: 'app_haie_supprimer')]
    public function haie_supprimer(EntityManagerInterface $entityManager, string $code,HaieRepository $haieRepository): Response
    {
        $haie= $haieRepository->find($code);
        if (!$haie) {
            return new Response('Ce type de haie n\'existe pas : '.$code);
        }
        else {
            $entityManager->remove($haie);
            $entityManager->flush();
            return new Response('Type de haie supprimé : '.$haie->getNom().' à '.$haie->getPrix(). '€');
        }

    }

}
