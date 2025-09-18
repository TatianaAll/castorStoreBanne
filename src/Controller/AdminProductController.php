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

class AdminProductController extends AbstractController
{
    #[Route('/admin/product/create', name: 'product_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier uploadé
            $uploadedFile = $form->get('image')->getData();

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                // Déplace le fichier dans public/uploads
                $uploadedFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );

                $product->setImage('uploads/' . $newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', "Nouveau produit enregistré avec succès !");
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/product/create.html.twig', [
            'formView' => $form->createView()
        ]);
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
