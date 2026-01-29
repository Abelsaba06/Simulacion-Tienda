<?php

namespace App\Controller;
use App\Service\ProductService;
use App\Service\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TeamService $teamservice, ProductService $productService): Response
    {
        $products = $productService->getProducts();
        $team = $teamservice->getTeams();
        return $this->render('page/index.html.twig', compact('team', 'products'));
    }

    #[Route('/about', name: 'about')]
    public function about(TeamService $teamservice): Response
    {
        $team = $teamservice->getTeams();
        return $this->render('page/about.html.twig', compact('team'));
    }

    #[Route('/service', name: 'service')]
    public function service(): Response
    {
        return $this->render('page/service.html.twig', []);
    }

    #[Route('/price', name: 'price')]
    public function price(): Response
    {
        return $this->render('page/price.html.twig', []);
    }

    #[Route('/team', name: 'team')]
    public function team(): Response
    {
        return $this->render('page/team.html.twig', []);
    }

    #[Route('/testimonial', name: 'testimonial')]
    public function testimonial(): Response
    {
        return $this->render('page/testimonial.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }
    #[Route('/buy', name: 'buy')]
    public function buy(): Response
    {
        return $this->render('cart/buy.html.twig', []);
    }

}
