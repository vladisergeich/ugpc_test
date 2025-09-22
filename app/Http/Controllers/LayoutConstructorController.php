<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{LayoutConstructor, EngravingOrder};
use Illuminate\Support\Facades\DB;

class LayoutConstructorController extends Controller
{
    public function create(Request $request)
    {
        $engravingOrder = EngravingOrder::findOrFail($request->engravingOrder['id']);

        $lastSequence = LayoutConstructor::where('engraving_order_id', $engravingOrder->id)->max('stream_number') ?? 0;

        $newStreams = collect(range(1, $request->qty))->map(fn($i) => [
            'engraving_order_id' => $engravingOrder->id,
            'stream_number' => $lastSequence + $i,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        LayoutConstructor::insert($newStreams);

        return back()->with([
            'message' => 'Ручьи добавлены',
            'engravingOrder' => $engravingOrder->load('layoutStreams'),
        ]);
    }

    public function update(Request $request)
    {
        $layoutStream = LayoutConstructor::findOrFail($request->id);
        $layoutStream->update($request->all());

        return back()->with([
            'message' => 'Ручей обновлен',
            'engravingOrder' => EngravingOrder::with('layoutStreams')
                ->findOrFail($request->engraving_order_id),
        ]);
    }

    public function destroy(Request $request)
    {
        LayoutConstructor::where('id', $request->id)->delete();

        DB::transaction(function () use ($request) {
            LayoutConstructor::where('engraving_order_id', $request->engraving_order_id)
                ->orderBy('stream_number')
                ->get()
                ->each(fn($stream, $index) => $stream->update(['stream_number' => $index + 1]));
        });

        return back()->with([
            'message' => 'Ручей удален',
            'engravingOrder' => EngravingOrder::with('layoutStreams')
                ->findOrFail($request->engraving_order_id),
        ]);
    }
}
