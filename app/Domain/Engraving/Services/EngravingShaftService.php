<?php

namespace App\Domain\Engraving\Services;

use App\Models\EngravingOrder;
use App\Models\EngravingOrderShaft;
use Illuminate\Support\Facades\DB;

class EngravingShaftService
{
    private const RELATIONS = ['engravingOrderShaft.shaft.warehouse'];

    public function addSections(EngravingOrder $engravingOrder, int $quantity): EngravingOrder
    {
        if ($quantity < 1) {
            return $engravingOrder->fresh()->load(self::RELATIONS);
        }

        $lastSequence = (int) $engravingOrder->engravingOrderShaft()->max('sequence_number');

        $sections = collect(range(1, $quantity))->map(fn ($index) => [
            'engraving_order_id' => $engravingOrder->id,
            'sequence_number' => $lastSequence + $index,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        EngravingOrderShaft::insert($sections);

        return $engravingOrder->fresh()->load(self::RELATIONS);
    }

    public function updateSection(EngravingOrderShaft $section, array $attributes): EngravingOrder
    {
        $section->update($attributes);

        return $section->engravingOrder->fresh()->load(self::RELATIONS);
    }

    public function deleteSection(EngravingOrderShaft $section): EngravingOrder
    {
        $engravingOrder = $section->engravingOrder;

        DB::transaction(function () use ($section, $engravingOrder) {
            $section->delete();

            $engravingOrder->engravingOrderShaft()
                ->orderBy('sequence_number')
                ->get()
                ->each(fn ($shaft, $index) => $shaft->update(['sequence_number' => $index + 1]));
        });

        return $engravingOrder->fresh()->load(self::RELATIONS);
    }

    public function returnShaft(EngravingOrderShaft $section, int $previousShaftId): EngravingOrder
    {
        EngravingOrderShaft::where('shaft_id', $previousShaftId)
            ->where('status', 'written_off')
            ->latest('write_off_date')
            ->first()?->update([
                'status' => 'in_progress',
                'write_off_date' => null,
            ]);

        $section->update([
            'shaft_id' => null,
            'status' => null,
        ]);

        return $section->engravingOrder->fresh()->load(self::RELATIONS);
    }
}
