<?php

namespace App\Product;

//use Doctrine\ORM\EntityManagerInterface;
use App\Product\ProductStorageInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Product\Payload\ProductPayload;

class ProductStorage implements ProductStorageInterface
{
    //private EntityManagerInterface $em;

    public function __construct(//EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private ProductPresenter $presenter,
    ) {
        //$this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll(): array
    {
        try {
//return $this->em->getRepository(Product::class)->findAll();
            $entities = $this->productRepository->list();
            return $this->presenter->presentCollection($entities);
        } catch (ProductStorageException $e) {
            throw new ProductStorageException($e->getMessage(), 500, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function save(ProductPayload $productPayload): array
    {
        $product = $this->productRepository->create($productPayload);
        return $this->presenter->present($product);
    }
}
