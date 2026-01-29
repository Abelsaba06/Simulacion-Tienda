<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\CartService;
/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getFromCart(CartService $cart): array
    {
        if (empty($cart->getCart())) {
            return [];
        }
        $ids = implode(',', array_keys($cart->getCart()));

        return $this->createQueryBuilder('p')
            ->andWhere("p.id in ($ids)")
            ->getQuery()
            ->getResult();
    }
}
