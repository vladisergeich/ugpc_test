<?php

namespace App\Domain\Engraving\Services;

use App\Domain\Engraving\Support\AttachmentBuilder;
use App\Domain\Engraving\Support\EngravingOrderSearch;
use App\Models\EngravingOrder;
use App\Models\MacroOrder;
use App\Models\Phase;
use App\Models\PhaseStage;
use App\Support\ValueObjects\DocumentAttachment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class EngravingOrderService
{
    private const SHOW_RELATIONS = [
        'engravingOrderShaft.shaft.warehouse',
        'order.items.item',
        'profile.primary',
        'profile.secondary',
        'profile.vendor',
        'layoutStreams',
        'repeatEngravingOrder',
    ];

    public function __construct(
        private readonly AttachmentBuilder $attachmentBuilder,
        private readonly EngravingOrderSearch $search
    ) {
    }

    public function getShowPayload(MacroOrder $macroOrder, EngravingOrder $engravingOrder): array
    {
        $engravingOrder->load(self::SHOW_RELATIONS);

        return [
            'macroOrder' => $macroOrder->load('shaftsInWork.shaft.warehouse', 'shaftsInWork.engravingOrder', 'customer'),
            'engravingOrder' => $engravingOrder,
            'engravingOrders' => $macroOrder->engravingOrders,
        ];
    }

    public function duplicateFromLast(int $macroOrderId): EngravingOrder
    {
        $lastEngravingOrder = EngravingOrder::where('macro_order_id', $macroOrderId)
            ->latest('id')
            ->select(['additional_options', 'parameters', 'type_work_parameters', 'technological_elements'])
            ->first();

        $nextSequence = (int) EngravingOrder::where('macro_order_id', $macroOrderId)->max('sequence_number');
        $nextSequence = $nextSequence ? $nextSequence + 1 : 1;

        $engravingOrder = EngravingOrder::updateOrCreate(
            ['macro_order_id' => $macroOrderId, 'sequence_number' => $nextSequence],
            $lastEngravingOrder ? $lastEngravingOrder->toArray() : []
        );

        return $engravingOrder->fresh()->load(self::SHOW_RELATIONS);
    }

    public function search(string $query, string $mode = 'batch'): Collection
    {
        return $this->search->search($query, $mode);
    }

    public function sendApplication(int $engravingOrderId): array
    {
        $engravingOrder = EngravingOrder::with([
            'macroOrder',
            'engraver',
            'engravingOrderShaft.shaft',
            'order.items.item',
            'profile.vendor',
        ])->findOrFail($engravingOrderId);

        $this->prepareInitialPhase($engravingOrder);

        $attachments = $this->attachmentBuilder->buildApplicationAttachments($engravingOrder);

        $this->dispatchAttachments('http://10.10.40.94:4415/ws/zayavka', [
            $attachments['application'],
            $attachments['shaftApplication'],
            $attachments['pdf'],
        ]);

        $engravingOrder->update(['engraving_request_date' => now()]);

        return [
            'engravingOrder' => $engravingOrder->fresh()->load(self::SHOW_RELATIONS),
            'pdf' => $attachments['pdf']->contents,
        ];
    }

    public function sendShaftInfo(int $engravingOrderId): EngravingOrder
    {
        $engravingOrder = EngravingOrder::with('engravingOrderShaft', 'order.items.item')
            ->findOrFail($engravingOrderId);

        $attachment = $this->attachmentBuilder->buildShaftInfoAttachment($engravingOrder);

        $this->dispatchAttachments('http://10.10.40.94:4415/ws/zayavka', [$attachment]);

        return $engravingOrder->fresh()->load(self::SHOW_RELATIONS);
    }

    public function sendStreamInfo(int $engravingOrderId): EngravingOrder
    {
        $engravingOrder = EngravingOrder::with('layoutStreams', 'order.items.item')
            ->findOrFail($engravingOrderId);

        $attachment = $this->attachmentBuilder->buildStreamInfoAttachment($engravingOrder);

        $this->dispatchAttachments('http://10.10.40.94:4415/ws/EskoVerstTest', [$attachment]);

        return $engravingOrder->fresh()->load(self::SHOW_RELATIONS);
    }

    public function updateEngravingOrder(EngravingOrder $engravingOrder, array $payload): EngravingOrder
    {
        $engravingOrder->fill($payload);
        $engravingOrder->save();

        return $engravingOrder->fresh()->load(self::SHOW_RELATIONS);
    }

    private function prepareInitialPhase(EngravingOrder $engravingOrder): void
    {
        DB::transaction(function () use ($engravingOrder) {
            $engravingOrder->engravingOrderShaft
                ->filter(fn ($shaft) => $shaft->shaft_id)
                ->each(function ($shaft) {
                    $shaft->final_diameter = $shaft->calculateFinalDiameter();
                    $shaft->save();

                    $phase = Phase::updateOrCreate(
                        ['engraving_order_shaft_id' => $shaft->id, 'sequence' => 1],
                        ['status' => Phase::STATUS_IN_PROGRESS]
                    );

                    PhaseStage::updateOrCreate([
                        'phase_id' => $phase->id,
                        'sequence' => 1,
                        'stage_id' => 14,
                        'status' => Phase::STATUS_IN_PROGRESS,
                    ]);
                });
        });
    }

    /**
     * @param  array<int, DocumentAttachment>  $attachments
     */
    private function dispatchAttachments(string $url, array $attachments): void
    {
        $request = Http::asMultipart();

        foreach ($attachments as $attachment) {
            $request = $attachment->attachTo($request);
        }

        $request->post($url);
    }
}
