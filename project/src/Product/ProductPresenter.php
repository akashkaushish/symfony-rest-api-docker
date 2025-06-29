<?php

// src/Product/ProductPresenter.php
namespace App\Product;

use App\Entity\Product;

class ProductPresenter
{
    /**
     * present
     *
     * @param  Product $product
     * @return array{ name: string, url: string, description: ?string, price: float }
     */
    public function present(Product $product): array
    {
        return [
            'name' => $product->getName(),
            'url' => '/api/v1/products/' . $product->getId(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            // add more fields if needed
        ];
    }

    /**
     * @param Product[] $products
     *  @return  array<array{name: string, url: string, description: string|null, price: float}>
     *  */
    public function presentCollection(array $products): array
    {
        return array_map([$this, 'present'], $products);
    }
}
