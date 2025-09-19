<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BasketController extends AbstractController
{

  //pour gerer le basket il faut une date de creation et un date d'update donc modifier la table basket avec ces 2 colonnes


  #[Route(
    path: '/basket/{id_user}/{id_product}/{redirect}',
    name: 'create_basket',
    requirements: ["id_user" => "\d+", "id_product" => "\d+"],
    defaults: ["redirect" => "product"]
  )]
  function createBasket(
    EntityManagerInterface $entityManager,
    BasketRepository $basketRepository,
    int $id_product,
    int $id_user,
    UserRepository $userRepository,
    ProductRepository $productRepository,
    string $redirect
  ): Response {

    //je cherche mon produit et mon User dans la BD
    $product = $productRepository->find($id_product);
    if (!$product) {
      throw $this->createNotFoundException('Produit introuvable');
    }
    $user = $userRepository->find($id_user);
    if (!$user) {
      throw $this->createNotFoundException('Utilisateur introuvable');
    }
    // Vérification si un panier existe déjà
    $existingBasket = $basketRepository->findOneBy(criteria: ['user_id' => $user]);

    if (!$existingBasket) {
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
        "Nouveau panier créé pour l\'utilisateur {$id_user}"
      );
    } else {
      //si on a deja un basket pour ce user on va ajouter le produit dedans
      $existingBasket->addProductId($product);
      $existingBasket->setUpdatedAt(new DateTime);

      $entityManager->persist($existingBasket);
      $entityManager->flush();

      $this->addFlash('success', "Produit ajouté au panier");
    }

    if ($redirect == 'basket') {
      return $this->redirectToRoute('show_basket', ['id_user' => $id_user]);
    } else {
      return $this->redirectToRoute('product_show', ['id' => $id_product]);
    }
  }



  #[Route(path: '/basket/{id_user}', name: 'show_basket', requirements: ["id_user" => "\d+"], methods: ['GET'])]
  function showBasket(BasketRepository $basketRepository, UserRepository $userRepository, int $id_user): Response
  {
    //check user
    $user = $userRepository->find($id_user);
    if (!$user) {
      throw $this->createNotFoundException('Utilisateur introuvable');
    }
    $basket = $basketRepository->findOneBy(['user_id' => $user]);
    if (!$basket) {
      return $this->render('/basket/show.html.twig', [
        'user' => $user,
        'basket' => null,
      ]);
    }

    return $this->render('/basket/show.html.twig', ['basket' => $basket, 'user' => $user]);
  }

  #[Route(path: '/basket/{id_user}/remove/{id_product}', name: 'remove_product', requirements: ["id_user" => "\d+", "id_product" => "\d+"], methods: ['GET'])]
  function deleteBasket(
    BasketRepository $basketRepository,
    UserRepository $userRepository,
    ProductRepository $productRepository,
    int $id_user,
    int $id_product,
    EntityManagerInterface $entityManager
  ) {
    $user = $userRepository->find($id_user);
    $basket = $basketRepository->findOneBy(['user_id' => $user]);

    if (!$basket) {
      $this->addFlash("error", "Ya pas basket");
      return $this->redirectToRoute("show_basket", ['id_user' => $id_user]);
    }

    $productToRemove = $productRepository->find($id_product);
    if (!$productToRemove) {
      $this->addFlash("error", "Produit introuvable");
      return $this->redirectToRoute("show_basket", ['id_user' => $id_user]);
    }

    // Suppression du produit dans le panier
    $basket->removeProductId($productToRemove);
    $basket->setUpdatedAt(new DateTime);

    $entityManager->flush();

    $this->addFlash("success", "Produit supprimé du panier");

    return $this->redirectToRoute("show_basket", ['id_user' => $id_user]);
  }
}
