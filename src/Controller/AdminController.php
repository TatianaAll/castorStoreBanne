<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère les rôles de l'utilisateur
        $roles = $user ? $user->getRoles() : [];

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'roles' => $roles, // Passe les rôles au template
        ]);
    }
}
