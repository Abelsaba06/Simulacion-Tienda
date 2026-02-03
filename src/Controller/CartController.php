<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CartService;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route(path: '/cart')]
final class CartController extends AbstractController
{
    private $doctrine;
    private $repository;
    private $cart;
    public function __construct(ManagerRegistry $doctrine, CartService $cart)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getRepository(Product::class);
        $this->cart = $cart;
    }

    #[Route('/delete/{id}/one', name: 'cart_delete_one', methods: ['GET', 'POST'])]
    public function cart_delete_one(int $id): Response
    {
        $cart = $this->cart->getCart();
        $currentQuantity = $cart[$id] ?? 0;

        if ($currentQuantity > 1) {
            $this->cart->update($id, $currentQuantity - 1);
        } else {
            $this->cart->remove($id);
        }
        return $this->redirectToRoute('cart');
    }

    #[Route('/delete/{id}', name: 'cart_delete', methods: ['GET', 'POST'])]
    public function cart_delete(int $id): Response
    {

        $product = $this->repository->find($id);
        $this->cart->remove($id);
        return $this->redirectToRoute('cart');

    }

    #[Route('/update/{id}/{quantity}', name: 'cart_update', methods: ['GET', 'POST'])]
    public function cart_update(int $id, int $quantity): Response
    {
        $product = $this->repository->find($id);
        $this->cart->update($id, $quantity);
        $data = [
            "id" => $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto(),
            "quantity" => $this->cart->getCart()[$product->getId()]
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/add/{id}', name: 'cart_add', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function cart_add(int $id): Response
    {
        $product = $this->repository->find($id);
        if (!$product)
            return new JsonResponse("[]", Response::HTTP_NOT_FOUND);
        $this->cart->add($id, 1);
        $data = [
            "id" => $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto(),
            "quantity" => $this->cart->getCart()[$product->getId()]
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/validate', name: 'cart_validate', methods: ['POST'])]
    public function cart_validate(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $this->cart->removeAll();
        return $this->redirectToRoute('index');
    }   

    #[Route('/', name: 'cart')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $products = $this->repository->getFromCart($this->cart);
        //hay que añadir la cantidad de cada producto
        $items = [];
        $totalCart = 0;
        foreach ($products as $product) {
            $item = [
                "id" => $product->getId(),
                "name" => $product->getName(),
                "price" => $product->getPrice(),
                "photo" => $product->getPhoto(),
                "quantity" => $this->cart->getCart()[$product->getId()]
            ];
            $totalCart += $item["quantity"] * $item["price"];
            $items[] = $item;
        }
        return $this->render('cart/index.html.twig', ['items' => $items, 'totalCart' => $totalCart]);
    }
}