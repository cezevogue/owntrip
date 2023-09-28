<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response


    {
        return $this->render('admin/dashboard.html.twig', [
            'title' => 'Dashboard'
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hachage du mot de passe
            // la méthode hashPassword attend en 1er argument l'entité à scanner pour vérifier qu'elle
            // implémente bien la PasswordAuthenticatedUserInterface et ainsi qu'il puisse verifier l'algorithme
            // de cryptage utilisé défini dans le security.yaml
            $mdp = $hasher->hashPassword($user, $form->get('password')->getData());

            // on recharge sur l'objet le nouveau de passe haché
            $user->setPassword($mdp);
            $manager->persist($user);
            $manager->flush();

        }


        return $this->render('admin/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {


        return $this->render('admin/login.html.twig', [

        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {


    }


}
