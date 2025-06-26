<?php

namespace App\Product;
use App\Product\Payload\ProductPayload;

interface ProductStorageInterface
{
    /**
     * @throws ProductStorageException
     *  @return  array<array{name: string, url: string, description: string|null, price: float}>
     */
    public function fetchAll(): array;

    /**
     * @throws ProductStorageException
     * @return array{ name: string, url: string, description: ?string, price: float }
     */
    public function save(ProductPayload $productPayload): array;
}