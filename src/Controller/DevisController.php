<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Devis;
use App\Entity\Tailler;
use App\Repository\HaieRepository;
use App\Repository\DevisRepository;
use App\Repository\TaillerRepository;


class DevisController extends AbstractController
{
    #[Route('/devis', name: 'app_devis')]
    public function index(Request $request, EntityManagerInterface $entityManager, HaieRepository $haieRepository, SessionInterface $session): Response
    {

    // Vérifier si l'utilisateur est authentifié
        if (!$this->getUser()) {
            // Utilisateur non authentifié, retourner simplement le résultat sans enregistrer dans la base de données

            return $this->render('devis/index.html.twig', [
                'message' => 'Veuillez vous connecter pour enregistrer le devis.',
            ]);
        }

        // Récupérer l'identifiant de la haie à partir de la requête
        $haieId = $request->request->get('haieId');
        $longueur = (float) $request->request->get('longueur');
        $hauteur = (float) $request->request->get('hauteur');


         // Récupérer le type d'utilisateur à partir de la session
         $typeUtilisateur = $session->get('typeUtilisateur');

        // Vérifier si l'identifiant de la haie est défini
        if ($haieId !== null) {
            // Trouver la haie correspondante dans la base de données
            $haie = $haieRepository->find($haieId);
            // Vérifier si la haie a été trouvée
            if ($haie !== null) {
                /// Calculer le prix du devis en fonction des données saisies
                 $prixUnitaire = $haie->getPrix();
                 $prix = $prixUnitaire * $longueur;
                 if ($hauteur > 1.5) {
                     $prix *= 1.5;
                 }
                 if ($typeUtilisateur === 'entreprise') {
                     $prix *= 0.90; // Appliquer la remise de 10% pour les entreprises
                 }

                 // Formater le prix pour afficher toujours une décimale
                 $prixFormate = number_format($prix, 2, '.', ''); // Format: 2 décimales, point comme séparateur décimal

                // Enregistrer les données du devis dans la base de données
                $user = $this->getUser(); // Récupérer l'utilisateur connecté
                $devis = new Devis();
                $devis->setUser($user);
                $devis->setDate(new \DateTime());
                $devis->setTypeUtilisateur($typeUtilisateur);

                // Calculer le montant total et le stocker dans l'entité Devis
                $montantTotal = $prix; // Vous pouvez inclure d'autres calculs si nécessaire
                $devis->setMontantTotal($montantTotal);

                $entityManager->persist($devis);

                // Enregistrer les données de tailler dans la base de données
                $tailler = new Tailler();
                $tailler->setHauteur($hauteur);
                $tailler->setLongueur($longueur);
                $tailler->setDevis($devis);
                $tailler->setHaie($haie);


                $entityManager->persist($tailler);
                $entityManager->flush();


                // Afficher le prix calculé dans la vue
                return $this->render('devis/index.html.twig', [
                    'prix' => $prix,
                    'haie' => $haie,
                    'longueur' => $longueur,
                    'hauteur' => $hauteur,
                    'devis' => $devis,
                    'montantTotal' => $montantTotal,
                    'typeUtilisateur' => $typeUtilisateur, // Assurez-vous de passer la variable typeUtilisateur
                ]);
            } else {
                // Afficher un message d'erreur si la haie n'a pas été trouvée
                return $this->render('devis/error.html.twig', [
                    'message' => 'Haie non trouvée',
                ]);
            }
        } else {
            // Afficher un message d'erreur si l'identifiant de la haie n'est pas défini
            return $this->render('devis/error.html.twig', [
                'message' => 'Identifiant de la haie non défini',
            ]);
        }
    }

    #[Route('/devis/show', name: 'app_devis_show')]
    public function show(
            Request $request,
            EntityManagerInterface $entityManager,
            DevisRepository $devisRepository,
            TaillerRepository $taillerRepository
        ): Response {
            // Récupérer tous les devis
            $devis = $devisRepository->findAll();

            // Récupérer tous les taillers avec les devis associés
            $taillers = $taillerRepository->findAllWithDevis();

            // Récuperer le nom de la haieId
            $haieId = $request->request->get('haieId');

            // Afficher le tableau
            return $this->render('devis/show.html.twig', [
                'devis' => $devis,
                'taillers' => $taillers,
                'haieId' => $haieId,
                'montantTotal' => 'montantTotal',
                'message' => 'Liste des devis',

            ]);
        }

        #[Route('/devis/delete/{id}' , name: 'app_devis_delete')]
        public function delete(
            Request $request,
            EntityManagerInterface $entityManager,
            DevisRepository $devisRepository,
            TaillerRepository $taillerRepository,
            $id
        ): Response {
            // Récupérer le devis à supprimer
            $devis = $devisRepository->find($id);

            // Vérifier si le devis existe
            if (!$devis) {
                throw $this->createNotFoundException('Le devis avec l\'id ' . $id . ' n\'existe pas.');
            }

            // Récupérer tous les taillers associés à ce devis
            $taillers = $taillerRepository->findBy(['devis' => $devis]);

            // Supprimer tous les taillers associés à ce devis
            foreach ($taillers as $tailler) {
                $entityManager->remove($tailler);
            }

            // Supprimer le devis lui-même
            $entityManager->remove($devis);
            $entityManager->flush();

            // Rediriger vers la liste des devis
            return $this->redirectToRoute('app_devis_mesdevis');
        }

   #[Route('/devis/edit/{id}', name: 'app_devis_edit')]
   public function edit(
           Request $request,
           EntityManagerInterface $entityManager,
           DevisRepository $devisRepository,
           TaillerRepository $taillerRepository,
           HaieRepository $haieRepository,
           $id
       ): Response {
           // Retrieve the Devis entity by its ID
           $devis = $devisRepository->find($id);

           if (!$devis) {
               throw $this->createNotFoundException('Le devis avec l\'id ' . $id . ' n\'existe pas.');
           }

           // Retrieve the associated Tailler entity/entities
           $taillers = $taillerRepository->findBy(['devis' => $devis]);

           // Retrieve all Haie entities to populate the dropdown
           $haies = $haieRepository->findAll();

           // Handle form submission
           if ($request->isMethod('POST')) {
               // Retrieve form data
               $typeUtilisateur = $request->request->get('typeUtilisateur');
               $haieId = $request->request->get('haieId');
               $hauteur = (float) $request->request->get('hauteur');
               $longueur = (float) $request->request->get('longueur');

               // Update typeUtilisateur if it has changed
               if ($typeUtilisateur !== $devis->getTypeUtilisateur()) {
                   $devis->setTypeUtilisateur($typeUtilisateur);
               }

               $haie = $haieRepository->find($haieId);

               if ($haie) {
                   // Update tailler entities
                   foreach ($taillers as $tailler) {
                       $tailler->setHaie($haie);
                       $tailler->setHauteur($hauteur);
                       $tailler->setLongueur($longueur);
                       $entityManager->persist($tailler);
                   }

                   // Calculate price
                   $prixUnitaire = $haie->getPrix();
                   $prix = $prixUnitaire * $longueur;
                   if ($hauteur > 1.5) {
                       $prix *= 1.5;
                   }
                   if ($devis->getTypeUtilisateur() === 'entreprise') {
                       $prix *= 0.90; // 10% discount for enterprises
                   }

                   $devis->setMontantTotal($prix);
                   $entityManager->persist($devis);
                   $entityManager->flush();

                   return $this->redirectToRoute('app_devis_mesdevis');
               }
           }

           return $this->render('devis/edit.html.twig', [
               'devis' => $devis,
               'taillers' => $taillers,
               'haies' => $haies,
               'hauteur' => $taillers[0]->getHauteur(),
               'longueur' => $taillers[0]->getLongueur(),
           ]);
       }


   //voir mes devis
    #[Route('/devis/mesdevis', name: 'app_devis_mesdevis')]
    public function mesdevis(
        Request $request,
        EntityManagerInterface $entityManager,
        DevisRepository $devisRepository,
        TaillerRepository $taillerRepository
    ): Response {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer tous les devis de l'utilisateur connecté
        $devis = $devisRepository->findBy(['user' => $user]);

        // Récupérer tous les taillers associés à ces devis
        $taillers = $taillerRepository->findAllWithDevis();

        // Afficher le tableau
        return $this->render('devis/mesdevis.html.twig', [
            'devis' => $devis,
            'taillers' => $taillers,
            'message' => 'Liste de vos devis',
        ]);
    }

}
