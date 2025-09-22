<?php

namespace App\Domain\Engraving\Support;

use App\Models\Item;
use App\Models\Order;
use App\Models\Shaft;
use Illuminate\Support\Collection;

class EngravingOrderSearch
{
    public function search(string $query, string $mode = 'batch'): Collection
    {
        if (mb_strlen($query) <= 2) {
            return collect();
        }

        return match ($mode) {
            'product' => $this->searchByProduct($query),
            'shaft' => $this->searchByShaft($query),
            default => $this->searchByBatch($query),
        };
    }

    private function searchByProduct(string $query): Collection
    {
        $item = Item::where('code', 'like', "%{$query}%")
            ->with('engravingOrders.order')
            ->first();

        return $item?->engravingOrders ?? collect();
    }

    private function searchByShaft(string $query): Collection
    {
        return Shaft::where('code', 'like', "%{$query}%")
            ->with('engravingOrdersShafts.engravingOrder.order')
            ->get()
            ->flatMap(fn ($shaft) => $shaft->engravingOrdersShafts->pluck('engravingOrder'))
            ->filter()
            ->unique('id')
            ->values();
    }

    private function searchByBatch(string $query): Collection
    {
        $order = Order::where('order_number', 'like', "%{$query}%")
            ->with('engravingOrders')
            ->first();

        return $order?->engravingOrders ?? collect();
    }
}
