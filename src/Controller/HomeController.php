<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; 
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Point;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $title         = 'Bienvenue sur notre site';
        $quiSommesNous = '<span style>Nous sommes une équipe passionnée par la qualité et l’innovation. 
        Depuis notre création, nous nous engageons à offrir les meilleurs produits à nos clients.';

        $query = $productRepository->createQueryBuilder('p')
                    ->orderBy('p.id', 'DESC')
                    ->getQuery();

        $products = $paginator->paginate(
            $query,                             
            $request->query->getInt('page', 1), 
            6                                   
        );

        $map = (new Map())
            ->center(new Point(46.903354, 1.888334))
            ->zoom(6);

        return $this->render('home/index.html.twig', [
            'title'         => $title,
            'quiSommesNous' => $quiSommesNous,
            'products'      => $products,
            'map'           => $map,
        ]);
    }
}
