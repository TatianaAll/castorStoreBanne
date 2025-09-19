<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer le terme de recherche dans l'URL (GET)
        $searchTerm = $request->query->get('q');

        // Récupérer les produits filtrés par la recherche
        $allProducts = $productRepository->findBySearchQuery($searchTerm);

        // Paginer les résultats
        $productToDisplay = $paginator->paginate(
            $allProducts,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('product/index.html.twig', [
            'title' => 'Produits',
            'products' => $productToDisplay,
            'searchTerm' => $searchTerm, // pour pré-remplir la barre
        ]);
    }

    #[Route('/product/{id}', name: 'product_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}