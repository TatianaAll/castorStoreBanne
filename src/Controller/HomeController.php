<?php

namespace App\Controller;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        $title         = 'Bienvenue sur notre site';
        $quiSommesNous = 'Nous sommes une équipe passionnée par la qualité et l’innovation. Depuis notre création, nous nous engageons à offrir les meilleurs produits à nos clients.';

        $products = $productRepository->findBy([], null, 6);

        return $this->render('home/index.html.twig', [
            'title'         => $title,
            'quiSommesNous' => $quiSommesNous,
            'products'      => $products,
        ]);

        // // $user est null si personne n'est connecté
        // return $this->render('home/index.html.twig', [
        //     'user' => $user,
        // ]);
    }
}