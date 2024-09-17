<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ChangePasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/{id}/edit', name: 'edit_user')]
    public function edit(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // If form is submitted and valid, update user information
                $entityManager->flush(); // Save changes to the database

                $this->addFlash('success', 'Your profile has been updated successfully.');

                return $this->redirectToRoute('app_profile');
            }

            return $this->render('profile/edit.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    #[Route('/profile/edit_password/{id}', name: 'edit_password')]
    public function editPassword(Request $request, User $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(ChangePasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $newPassword = $passwordHasher->hashPassword($user, $data['newPassword']);
                $user->setPassword($newPassword);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Password updated successfully.');

                return $this->redirectToRoute('profile'); // Adjust the route as necessary
            }

            return $this->render('profile/edit_password.html.twig', [
                'form' => $form->createView(),
            ]);
        }
}
