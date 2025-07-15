<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Product\Payload\ProductPayload;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Product\ProductStorageInterface;
use Symfony\Component\HttpFoundation\{JsonResponse, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/v1')]
final class ProductController extends AbstractController
{
    public function __construct(private readonly ProductStorageInterface $productStorage,)
    {
    }

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function getProducts(CacheInterface $cache): JsonResponse
    {
        //$products = $this->productStorage->fetchAll();
        $products = $cache->get('product_list', function (ItemInterface $item) {
            $item->expiresAfter(3600); // cache for 1 hour
            return $this->productStorage->fetchAll(); // expensive DB call
        });

        return new JsonResponse([
        'count'    => count($products),
        'next'     => null,
        'previous' => null,
        'results'  => $products,
        ]);
    }

    #[Route('/addproduct', name: 'addproduct', methods: ['POST'])]
    public function addProduct(#[MapRequestPayload] ProductPayload $productPayload,): JsonResponse
    {
        $product = $this->productStorage->save($productPayload);
        return new JsonResponse($product);
    }
}
