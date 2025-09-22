<?php

namespace App\Services;

use App\Models\{
    Phase,
    PhaseStage,
    EngravingOrder,
    EngravingOrderShaft,
    PhaseStageParameter
};
use Illuminate\Support\Facades\Http;

class EngravingOrderService
{
    public function createStages(EngravingOrderShaft $shaft, $orderStage,$preCopper): void
    {
        $shaft->load('engravingOrder', 'shaft');
        $stages = $this->calculateStages($shaft, $orderStage,$preCopper);

        $this->updateOrCreateStages($shaft, $stages, $orderStage->phase->sequence);
    }

    private function getCopperReserve(int $format): int
    {
        return collect([
            500 => 35,
            600 => 38,
            700 => 40,
            800 => 43,
            900 => 45,
        ])->first(fn($val, $key) => $format <= $key) ?? 0;
    }

    public function calculateCopperPlating(EngravingOrderShaft $shaft): float
    {
        $plating = (($shaft->final_diameter - $shaft->shaft->diameter) * 1000 * 1.05) / 2;
        if ($plating > 150) {
            $plating += $this->getCopperReserve($shaft->engravingOrder->format);
        }
        return $plating;
    }

    private function calculateStages(EngravingOrderShaft $shaft, $orderStage,$preCopper): array
    {
        $copperPlating = round($this->calculateCopperPlating($shaft));
        $isSteel = $shaft->shaft->type === 'Сталь';
        $baseSize = $isSteel ? 350 : 500;
        $reserve = $this->getCopperReserve($shaft->engravingOrder->format);

        $qty = floor(($copperPlating + floor($copperPlating / $baseSize) * $reserve) / $baseSize);
        $qty = max($isSteel ? 2 : 1, $qty);
        if ($preCopper && $orderStage->stage_id === 7) $qty++;

        return array_map(
            fn($i) => ['stages' => $this->getStageIds($shaft, $i, $orderStage, $qty,$preCopper)],
            range(1, $qty)
        );
    }

    private function getStageIds($shaft, $stageNumber, $stage, $totalStages,$preCopper): array
    {
        $type = $shaft->shaft->type;
        $first = $stage->phase->sequence === 1;
        $last = $stageNumber === $totalStages;
        $many = $totalStages > 1;

        $start = [14];
        $common = [3, 2, 5];
        $finish = [12, 7, 9, 8, 10, 11, 13];

        $id = $stage->stage_id;

        if ($preCopper) {
            if ($id === 3) {
                if ($type === 'Хром') $start[] = 1;
                if ($first && $many) return array_merge($start, $common, [6]);
                if (!$last) return [3, 2, 5, 6];
                return $many ? array_merge($common, $finish) : array_merge($start, $common, $finish);
            }
            if ($id === 7) return $last ? [2, 5, 7, 9, 8, 10, 11, 13] : [3, 2, 5];
        } else {
            if ($first && $many) {
                $start[] = $type === 'Хром' ? 1 : 3;
                return array_merge($start, [2, 6]);
            }
            if (!$last) return [3, 2, 6];
            return $many 
                ? ($type === 'Хром' ? [1,3, 4, ...$finish] : [3, 4, ...$finish])
                : array_merge(
                    [14,...($type === 'Хром' ? [1] : []), 3, 4], 
                    $finish
                );
        }
        return [];
    }

    public function updateOrCreateStages(EngravingOrderShaft $shaft, array $stages, int $startSeq): void
    {
        Phase::where('engraving_order_shaft_id', $shaft->id)
            ->where('sequence', '>', $startSeq)
            ->delete();

        foreach ($stages as $stageData) {
            $phase = Phase::updateOrCreate(
                ['engraving_order_shaft_id' => $shaft->id, 'sequence' => $startSeq],
                ['status' => 'in_progress']
            );
            $this->createEngravingStages($phase, $stageData);
            $startSeq++;
        }
    }

    public function createEngravingStages(Phase $phase, array $stageData): void
    {
        $phase->stages()->whereNotIn('stage_id', $stageData['stages'])->where('status',null)->delete();

        foreach ($stageData['stages'] as $i => $id) {
            PhaseStage::updateOrCreate(
                ['phase_id' => $phase->id, 'stage_id' => $id],
                ['sequence' => $i + 1]
            );
        }
    }
}
