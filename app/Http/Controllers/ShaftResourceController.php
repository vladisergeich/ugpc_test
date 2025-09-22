<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EngravingOrder, EngravingOrderShaft, ShaftResource, MacroOrder};
use Illuminate\Support\Facades\DB;

class ShaftResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $macroOrder = MacroOrder::with('shaftsInWork.engravingOrder','shaftsInWork.resources')->find($request->macro['id']);

        foreach ($macroOrder->shaftsInWork as $shaft) {
            $lastSequence = ShaftResource::where('engraving_order_shaft_id', $shaft->id)->max('sequence') ?? 0;

            $resource = ShaftResource::updateOrCreate(
                ['engraving_order_shaft_id' => $shaft->id,'sequence' => $lastSequence + 1],
                [   'order_number' => $request->resource['order'],
                    'print_date' => $request->resource['date'],
                    'footage' => $request->resource['footage'],
                ],
            );
        }

        return back()->with([
            'macroOrder' => $macroOrder,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        ShaftResource::findOrFail($request->resource['id'])->delete();

        DB::transaction(function () use ($request) {
            ShaftResource::where('engraving_order_shaft_id', $request->resource['engraving_order_shaft_id'])
                ->orderBy('sequence')
                ->get()
                ->each(fn($shaft, $index) => $shaft->update(['sequence' => $index + 1]));
        });

        $macroOrder = MacroOrder::with('shaftsInWork.engravingOrder','shaftsInWork.resources')->find($request->macro['id']);

        return back()->with([
            'macroOrder' => $macroOrder,
        ]);
    }
}
