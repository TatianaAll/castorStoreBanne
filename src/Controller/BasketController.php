<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BasketController extends AbstractController
{

  //pour gerer le basket il faut une date de creation et un date d'update donc modifier la table basket avec ces 2 colonnes


  #[Route(path: '/basket/{id_user}/{id_product}', name: 'basket', requirements: ["id_user" => "\d+", "id_product" => "\d+"])]
  function createBasket(
    EntityManagerInterface $entityManager,
    BasketRepository $basketRepository,
    int $id_product,
    int $id_user,
    UserRepository $userRepository,
    ProductRepository $productRepository
  ): Response {
    //je cherche mon produit et mon User dans la BD
    $product = $productRepository->find($id_product);
    $user = $userRepository->find($id_user);
    if (!$user) {
      throw $this->createNotFoundException('Utilisateur introuvable');
    }
    // Vérification si un panier existe déjà
    $existingBasket = $basketRepository->findOneBy(criteria: ['user_id' => $user]);

    if ($existingBasket) {
      return new Response("Un panier existe déjà pour cet utilisateur (id {$id_user}).");
    }

    // Création du panier
    $basket = new Basket();
    //je defini l'user pour ma table Basket
    $basket->setUserId($user);
    $basket->setCreatedAt(new DateTime);
    //je defini le produit pour ma table pivot
    $basket->addProductId($product);

    $entityManager->persist($basket);
    $entityManager->flush();

    $this->addFlash(
      'success',
      'Nouveau panier créé pour l\'utilisateur {$id_user}'
    );

    return $this->redirectToRoute('home');
  }
}
