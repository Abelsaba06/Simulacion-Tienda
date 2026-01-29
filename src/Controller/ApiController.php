<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Product;

#[Route('/api')]
final class ApiController extends AbstractController
{
    #[Route('/show/{id}', name: 'api_show')]
    public function show(ManagerRegistry $doctrine,int $id): JsonResponse
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
        $data = [
            "id"=> $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto()
         ];
        return $this->json($data, Response::HTTP_OK);
    }
}
