<?php

namespace App\Domain\Orders\DataTransferObjects;

class SaveOrderData
{
    public function __construct(
        public readonly string $orderNumber,
        public readonly int $engravingOrderId
    ) {
    }
}
