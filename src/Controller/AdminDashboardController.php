<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminDashboardController extends AbstractController
{
  #[Route(path: '/admin', name: 'admin_dashboard', methods: ['GET'])]
  #[IsGranted('ROLE_ADMIN')]
  public function getAdminDashboard(UserRepository $userRepository, ProductRepository $productRepository): Response
  {
    $allProducts = $productRepository->findAll();
    $numberOfProducts = count($allProducts);

    // Récupère l'utilisateur connecté
    $user = $this->getUser();

    // Récupère les rôles de l'utilisateur
    $roles = $user ? $user->getRoles() : [];

    $allUsers = $userRepository->findAll();
    $numberOfUsers = count($allUsers);

    return $this->render('/admin/dashboard.html.twig', ['numberProduct' => $numberOfProducts, 
    'numberUser' => $numberOfUsers, 'roles' => $roles ]);
  }
}
