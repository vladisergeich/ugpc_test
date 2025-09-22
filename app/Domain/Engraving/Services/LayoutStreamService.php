<?php

namespace App\Domain\Engraving\Services;

use App\Models\EngravingOrder;
use App\Models\LayoutConstructor;
use Illuminate\Support\Facades\DB;

class LayoutStreamService
{
    private const RELATIONS = ['layoutStreams'];

    public function addStreams(EngravingOrder $engravingOrder, int $quantity): EngravingOrder
    {
        if ($quantity < 1) {
            return $engravingOrder->fresh()->load(self::RELATIONS);
        }

        $lastSequence = (int) $engravingOrder->layoutStreams()->max('stream_number');

        $streams = collect(range(1, $quantity))->map(fn ($index) => [
            'engraving_order_id' => $engravingOrder->id,
            'stream_number' => $lastSequence + $index,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        LayoutConstructor::insert($streams);

        return $engravingOrder->fresh()->load(self::RELATIONS);
    }

    public function updateStream(LayoutConstructor $stream, array $attributes): EngravingOrder
    {
        $stream->update($attributes);

        return $stream->engravingOrder->fresh()->load(self::RELATIONS);
    }

    public function deleteStream(LayoutConstructor $stream): EngravingOrder
    {
        $engravingOrder = $stream->engravingOrder;

        DB::transaction(function () use ($stream, $engravingOrder) {
            $stream->delete();

            $engravingOrder->layoutStreams()
                ->orderBy('stream_number')
                ->get()
                ->each(fn ($item, $index) => $item->update(['stream_number' => $index + 1]));
        });

        return $engravingOrder->fresh()->load(self::RELATIONS);
    }
}
