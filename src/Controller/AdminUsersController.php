<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminUsersController extends AbstractController
{
  #[Route(path: 'admin/users/allProducts', name: 'all_users', methods: ['GET'])]
  public function getAllUsers(UserRepository $userRepository): Response
  {
    $users = $userRepository->findAll();
    return $this->render('admin/users.html.twig', ['users' => $users]);
  }

  #[Route(path: '/admin/user/{id}/delete', name: 'delete_user', requirements: ['id' => "\d+"], methods: ['GET'])]
  public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
  {
    $userToDelete = $userRepository->find($id);
    if (!$userToDelete) {
      $this->addFlash("error", "Cet utilisateur n'existe pas :(");
      return $this->redirectToRoute("all_users");
    }
    $entityManager->remove($userToDelete);
    $entityManager->flush();

    $this->addFlash('success', 'Utilisateur supprimé');
    return $this->redirectToRoute('all_users');
  }
}
