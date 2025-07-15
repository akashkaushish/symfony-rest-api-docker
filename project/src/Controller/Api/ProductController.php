<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Product\Payload\ProductPayload;
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
    public function getProducts(): JsonResponse
    {
        $products = $this->productStorage->fetchAll();

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
