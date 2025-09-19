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
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Circle;
use Symfony\UX\Map\InfoWindow;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $title         = 'Bienvenue sur notre site';
        $quiSommesNous = 'Nous sommes une équipe passionnée par la qualité et l’innovation. Depuis notre création, nous nous engageons à offrir les meilleurs produits à nos clients.';

        $query = $productRepository->createQueryBuilder('p')
                    ->orderBy('p.id', 'DESC')
                    ->getQuery();

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        $map = (new Map())
            ->center(new Point(44.840834, -0.576517))
            ->zoom(14)
            ->addMarker(new Marker(
        position: new Point(44.841318, -0.562080),
        title: 'MyDigitalSchool'
            ))
            ->addMarker(new Marker(
        position: new Point(44.841739, -0.569191),
        title: "Miroir d'eau"
            ))
            ->addMarker(new Marker(
        position: new Point(44.862812420438345, -0.5497545203372735),
        title: 'Cité du Vin'
            ))
            ->addMarker(new Marker(
        position: new Point(44.85734674294928, -0.5611078089140751),
        title: 'Brasserie des chartrons'
            ))
            ->fitBoundsToMarkers();

        return $this->render('home/index.html.twig', [
            'title'         => $title,
            'quiSommesNous' => $quiSommesNous,
            'products'      => $products,
            'map'           => $map,
        ]);
    }
}
