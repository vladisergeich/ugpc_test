<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EngravingOrder, EngravingOrderShaft, Shaft};
use Illuminate\Support\Facades\DB;

class EngravingOrderShaftController extends Controller
{
    public function create(Request $request)
    {
        $engravingOrder = EngravingOrder::findOrFail($request->engravingOrder['id']);

        $lastSequence = EngravingOrderShaft::where('engraving_order_id', $engravingOrder->id)->max('sequence_number') ?? 0;

        $newShafts = collect(range(1, $request->qty))->map(fn($i) => [
            'engraving_order_id' => $engravingOrder->id,
            'sequence_number' => $lastSequence + $i,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        EngravingOrderShaft::insert($newShafts);

        return back()->with([
            'message' => 'Секции добавлены',
            'engravingOrder' => $engravingOrder->load('engravingOrderShaft.shaft.warehouse'),
        ]);
    }

    public function getFreeShafts(Request $request)
    {
        return response()->json(Shaft::where('state','free')->select('id', 'code')->get());
    }

    public function update(Request $request)
    {
        $engravingOrderShaft = EngravingOrderShaft::findOrFail($request->id);
        
        $engravingOrder = EngravingOrder::find($request->engraving_order_id);
        $engravingOrderShaft->update($request->all());
        
        
        return back()->with([
            'message' => 'Секция удалена',
            'engravingOrder' => $engravingOrder->load('engravingOrderShaft.shaft.warehouse'),
        ]);
    }

    public function destroy(Request $request)
    {
        EngravingOrderShaft::where('id', $request->id)->delete();

        DB::transaction(function () use ($request) {
            EngravingOrderShaft::where('engraving_order_id', $request->engraving_order_id)
                ->orderBy('sequence_number')
                ->get()
                ->each(fn($shaft, $index) => $shaft->update(['sequence_number' => $index + 1]));
        });

        return back()->with([
            'message' => 'Секция удалена',
            'engravingOrder' => EngravingOrder::with('engravingOrderShaft.shaft.warehouse')
                ->findOrFail($request->engraving_order_id),
        ]);
    }

    public function returnShaft(Request $request)
    {
        EngravingOrderShaft::where('shaft_id', $request->shaft_id)
            ->where('status', 'written_off')
            ->latest('write_off_date')
            ->first()?->update([
                'status' => 'in_progress',
                'write_off_date' => null,
            ]);
    
        EngravingOrderShaft::where('id', $request->id)
            ->update(['shaft_id' => null,'status' => null]);
    
        return back()->with([
            'message' => 'Секция обновлена',
            'engravingOrder' => EngravingOrder::with('engravingOrderShaft.shaft.warehouse')
                ->findOrFail($request->engraving_order_id),
        ]);
    }
}
