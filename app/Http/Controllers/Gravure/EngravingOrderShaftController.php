<?php

namespace App\Http\Controllers\Gravure;

use App\Domain\Engraving\Services\EngravingShaftService;
use App\Http\Controllers\Controller;
use App\Http\Requests\EngravingOrderShafts\CreateEngravingOrderShaftRequest;
use App\Http\Requests\EngravingOrderShafts\DeleteEngravingOrderShaftRequest;
use App\Http\Requests\EngravingOrderShafts\ReturnEngravingOrderShaftRequest;
use App\Http\Requests\EngravingOrderShafts\UpdateEngravingOrderShaftRequest;
use App\Models\EngravingOrder;
use App\Models\EngravingOrderShaft;
use App\Models\Shaft;
use Illuminate\Http\JsonResponse;

class EngravingOrderShaftController extends Controller
{
    public function __construct(private readonly EngravingShaftService $shaftService)
    {
    }

    public function create(CreateEngravingOrderShaftRequest $request)
    {
        $engravingOrder = EngravingOrder::findOrFail($request->validated('engraving_order_id'));

        $updated = $this->shaftService->addSections(
            $engravingOrder,
            $request->validated('quantity')
        );

        return back()->with([
            'message' => 'Секции добавлены',
            'engravingOrder' => $updated,
        ]);
    }

    public function getFreeShafts(): JsonResponse
    {
        $shafts = Shaft::where('state', 'free')->get(['id', 'code']);

        return response()->json($shafts);
    }

    public function update(UpdateEngravingOrderShaftRequest $request)
    {
        $engravingOrderShaft = EngravingOrderShaft::findOrFail($request->validated('id'));

        $engravingOrder = $this->shaftService->updateSection(
            $engravingOrderShaft,
            $request->payload()
        );

        return back()->with([
            'message' => 'Секция обновлена',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function destroy(DeleteEngravingOrderShaftRequest $request)
    {
        $engravingOrderShaft = EngravingOrderShaft::findOrFail($request->validated('id'));

        $engravingOrder = $this->shaftService->deleteSection($engravingOrderShaft);

        return back()->with([
            'message' => 'Секция удалена',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function returnShaft(ReturnEngravingOrderShaftRequest $request)
    {
        $engravingOrderShaft = EngravingOrderShaft::findOrFail($request->validated('id'));

        $engravingOrder = $this->shaftService->returnShaft(
            $engravingOrderShaft,
            $request->validated('shaft_id')
        );

        return back()->with([
            'message' => 'Секция обновлена',
            'engravingOrder' => $engravingOrder,
        ]);
    }
}
