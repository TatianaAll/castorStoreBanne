<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeControllerTestController extends AbstractController
{
    #[Route('/home/controller/test', name: 'app_home_controller_test')]
    public function index(): Response
    {
        return $this->render('home_controller_test/index.html.twig', [
            'controller_name' => 'HomeControllerTestController',
        ]);
    }
}
