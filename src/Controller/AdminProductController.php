<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminProductController extends AbstractController {

  #[Route(path: '/admin/product/create', name: 'product_create', methods:['POST', 'GET'])]
  public function createProduct(Request $request, EntityManagerInterface $entityManager): Response {
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
    return $this->render('admin/product/create.html.twig', ["formView"=>$formView]);

  }
}