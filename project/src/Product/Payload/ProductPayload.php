<?php

declare(strict_types=1);

namespace App\Product\Payload;

use Symfony\Component\Validator\Constraints as Assert;

final class ProductPayload
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,

        #[Assert\NotBlank]
        public readonly string $description,

        #[Assert\Positive]
        public readonly float $price,
    ) {}
}