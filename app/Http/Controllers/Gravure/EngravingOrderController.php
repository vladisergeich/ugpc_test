<?php

namespace App\Http\Controllers\Gravure;

use App\Domain\Engraving\Services\EngravingOrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\EngravingOrders\EngravingOrderDuplicateRequest;
use App\Http\Requests\EngravingOrders\EngravingOrderSearchRequest;
use App\Http\Requests\EngravingOrders\SendEngravingApplicationRequest;
use App\Http\Requests\EngravingOrders\SendEngravingShaftInfoRequest;
use App\Http\Requests\EngravingOrders\SendEngravingStreamInfoRequest;
use App\Http\Requests\EngravingOrders\UpdateEngravingOrderRequest;
use App\Models\EngravingOrder;
use App\Models\MacroOrder;
use Inertia\Inertia;
use Inertia\Response;

class EngravingOrderController extends Controller
{
    public function __construct(private readonly EngravingOrderService $engravingOrderService)
    {
    }

    public function show(MacroOrder $macroOrder, EngravingOrder $engravingOrder): Response
    {
        return Inertia::render(
            'GravureDatabase/EngravingOrder/Show',
            $this->engravingOrderService->getShowPayload($macroOrder, $engravingOrder)
        );
    }

    public function create(EngravingOrderDuplicateRequest $request)
    {
        $engravingOrder = $this->engravingOrderService->duplicateFromLast(
            $request->validated('macro_order_id')
        );

        return redirect()
            ->route('engravingOrders.show', [
                'macroOrder' => $engravingOrder->macro_order_id,
                'engravingOrder' => $engravingOrder->id,
            ])
            ->with([
                'message' => 'Дубль создан',
                'engravingOrder' => $engravingOrder,
            ]);
    }

    public function search(EngravingOrderSearchRequest $request): Response
    {
        $validated = $request->validated();
        $search = $validated['search'] ?? '';
        $mode = $validated['mode'] ?? 'batch';

        $results = $search !== ''
            ? $this->engravingOrderService->search($search, $mode)
            : collect();

        return Inertia::render('GravureDatabase/EngravingOrder/Show', [
            'results' => $results,
            'search' => $search,
            'mode' => $mode,
        ]);
    }

    public function sendApplication(SendEngravingApplicationRequest $request)
    {
        $result = $this->engravingOrderService->sendApplication(
            $request->validated('engraving_order_id')
        );

        return back()->with([
            'message' => 'Заявка отправлена',
            'pdf' => $result['pdf'],
            'engravingOrder' => $result['engravingOrder'],
        ]);
    }

    public function sendShaftInfo(SendEngravingShaftInfoRequest $request)
    {
        $engravingOrder = $this->engravingOrderService->sendShaftInfo(
            $request->validated('engraving_order_id')
        );

        return back()->with([
            'message' => 'Информация по валам отправлена',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function sendStreamInfo(SendEngravingStreamInfoRequest $request)
    {
        $engravingOrder = $this->engravingOrderService->sendStreamInfo(
            $request->validated('engraving_order_id')
        );

        return back()->with([
            'message' => 'Информация по валам отправлена',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function update(UpdateEngravingOrderRequest $request)
    {
        $engravingOrder = EngravingOrder::findOrFail($request->validated('id'));

        $updated = $this->engravingOrderService->updateEngravingOrder(
            $engravingOrder,
            $request->validatedPayload()
        );

        return back()->with([
            'message' => 'Updated successfully',
            'engravingOrder' => $updated,
        ]);
    }
}
