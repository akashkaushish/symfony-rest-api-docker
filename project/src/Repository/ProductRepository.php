<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Product\Payload\ProductPayload;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     *
     * @return Product[]
     */
    public function list(): array
    {
        return $this->findAll();
    }


    /**
     * create
     *
     * @param  ProductPayload $payload
     * @return Product
     */
    public function create(ProductPayload $payload): Product
    {
        $product = new Product($payload->name, $payload->description, $payload->price);
        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();
        return $product;
    }
}
