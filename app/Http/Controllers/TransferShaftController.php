<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transfer, TransferShaft, Shaft};
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransferShaftController extends Controller
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
        $transfer = Transfer::findOrFail($request->transfer['id']);

        $lastSequence = TransferShaft::where('transfer_id', $transfer->id)->max('sequence') ?? 0;

        foreach ($request->shafts as $shaft) {

            $transferShaft = TransferShaft::updateOrCreate(
                ['transfer_id' => $transfer->id,'engraving_order_id' => $shaft['engraving_order']['id'] ?? null,'shaft_id' => $shaft['id']],
                [   'sequence' => $lastSequence + 1,],
            );

            $lastSequence++;
        }

        return back()->with([
            'transfer' => $transfer->load('shafts.shaft'),
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
        $transferShaft = TransferShaft::findOrFail($request->id);
        
        $transfer = Transfer::findOrFail($request->transfer_id);
        $transferShaft->update($request->all());
        
        
        return back()->with([
            'transfer' => $transfer->load('shafts.shaft'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        TransferShaft::where('id', $request->id)->delete();

        DB::transaction(function () use ($request) {
            TransferShaft::where('transfer_id', $request->engraving_order_id)
                ->orderBy('sequence')
                ->get()
                ->each(fn($shaft, $index) => $shaft->update(['sequence' => $index + 1]));
        });

        return back()->with([
            'transfer' => Transfer::with('shafts.shaft')->findOrFail($request->transfer_id),
        ]);
    }
}
