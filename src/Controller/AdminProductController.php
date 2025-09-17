<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminProductController extends AbstractController
{

  #[Route(path: '/admin/product/create', name: 'product_create', methods: ['POST', 'GET'])]
  public function createProduct(Request $request, EntityManagerInterface $entityManager): Response
  {
    //new instance of product
    $product = new Product();

    //make:form ProductType before, to create a form based on the entity Product
    //formCreateProduct is an instance of the form create with productType
    $formCreateProduct = $this->createForm(ProductType::class, $product);
    //with handle request we get the POST request and interprete
    $formCreateProduct->handleRequest($request);

    if ($formCreateProduct->isSubmitted() && $formCreateProduct->isValid()) {
      //entity manager is the ORM manager and permit to persist then flush in DB
      $entityManager->persist($product);
      $entityManager->flush();

      $this->addFlash('success', "Nouveau produit enregistré");
      return $this->redirectToRoute('admin_dashboard');
    }
    $formView = $formCreateProduct->createView();
    return $this->render('admin/product/create.html.twig', ["formView" => $formView]);
  }

  #[Route(path: 'admin/product/allProducts', name: 'all_products', methods: ['GET'])]
  public function getAllProducts(ProductRepository $productRepository): Response
  {
    $products = $productRepository->findAll();
    return $this->render('admin/product/list.html.twig', ['products' => $products]);
  }

  #[Route(path: 'admin/product/{id}/update', name: 'update_product', requirements: ["id" => "\d+"], methods: ["GET", "POST"])]
  public function updateProduct(
    int $id,
    ProductRepository $productRepository,
    Request $request,
    EntityManagerInterface $entityManager
  ): Response {
    $productToUpdate = $productRepository->find($id);

    if (!$productToUpdate) {
      $this->addFlash("error", "Ce produit exceptionnel n'existe pas :(");
      return $this->redirectToRoute("all_products");
    }

    //make:form ProductType before, to create a form based on the entity Product
    //formCreateProduct is an instance of the form create with productType
    $formUpdateProduct = $this->createForm(ProductType::class, $productToUpdate);

    //with handle request we get the POST request and interprete
    $formUpdateProduct->handleRequest($request);

    if ($formUpdateProduct->isSubmitted() && $formUpdateProduct->isValid()) {
      //entity manager is the ORM manager and permit to persist then flush in DB
      $entityManager->persist($productToUpdate);
      $entityManager->flush();

      $this->addFlash('success', "Modification du produit enregistrée");
      return $this->redirectToRoute('all_products');
    }
    $formView = $formUpdateProduct->createView();
    return $this->render('admin/product/update.html.twig', ["formView" => $formView, "productToUpdate" => $productToUpdate]);
  }

  #[Route(path: '/admin/product/{id}/delete', name: 'delete_product', requirements: ['id' => "\d+"], methods: ['GET'])]
  public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
  {
    $productToDelete = $productRepository->find($id);

    if (!$productToDelete) {
      $this->addFlash("error", "Ce produit exceptionnel n'existe pas :(");
      return $this->redirectToRoute("all_products");
    }

    $entityManager->remove($productToDelete);
    $entityManager->flush();

    $this->addFlash("success", "Produit supprimé");

    return $this->redirectToRoute("all_products");
  }
}
