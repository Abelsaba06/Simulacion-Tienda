<?php
namespace App\Service;
   
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
   
class ProductService{
    private $doctrine;
   
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    public function getProducts(): ?array{
        $repository = $this->doctrine->getRepository(Product::class);
        return $repository->findAll();
    }
}
