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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AdminProductController extends AbstractController
{

  #[Route(path: '/admin/product/create', name: 'product_create', methods: ['POST', 'GET'])]
  public function createProduct(
    Request $request,
    EntityManagerInterface $entityManager,
    SluggerInterface $slugger,
    #[Autowire('%kernel.project_dir%/public/uploads/')] string $fileDirectory
  ): Response {
    $product = new Product();
    $formCreateProduct = $this->createForm(ProductType::class, $product);
    $formCreateProduct->handleRequest($request);

    if ($formCreateProduct->isSubmitted()) {
      //dd($formCreateProduct->isValid(), $formCreateProduct->getErrors(true, false));
      if ($formCreateProduct->isSubmitted() && $formCreateProduct->isValid()) {
            $uploadedFile = $formCreateProduct->get('image')->getData();
            //dd($uploadedFile);
            if ($uploadedFile) {
              $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
              $safeFilename = $slugger->slug($originalFilename);
              $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

              try {
                $uploadedFile->move($fileDirectory, $newFilename);
              } catch (FileException $e) {
                // Handle exception if something happens during file upload
              }

              $product->setImage("/uploads/".$newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', "Nouveau produit enregistré");
            return $this->redirectToRoute('admin_dashboard');
          }
    }
    

    $form = $formCreateProduct->createView();
    return $this->render('admin/product/create.html.twig', ["form" => $form]);
  }

  ###CREEEEEEEEEEEEEEEEEEEEEEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAATTTTTTTTTTTTTTTTTTTTTTTEEEEEEEEEEEEEEEEEEEEE NNNNNNNNNNNNNNNNEEEEEEEEEEEEEEEEEEEEEEEEEEEEWWWWWWWWWWWWWWWWWWWW###

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
