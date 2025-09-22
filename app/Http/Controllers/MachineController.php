<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MachineCenter, EngravingOrder, WorkShiftCode,OperationLedgerEntry};
use Inertia\Inertia;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Machine/Index', [
            'machines' => MachineCenter::all(),
        ]);
    }

    public function show(MachineCenter $machine)
    {
        Inertia::share('workShift', WorkShiftCode::where('status','processing')->first());

        return Inertia::render('Machine/Show', [
            'machine' => $machine->load([
                'shaftsInWarehouse.phase.engravingOrderShaft' => [
                    'engravingOrder', 
                    'shaft'
                ],
                'consumpShaft.engravingOrderShaft' => [
                    'engravingOrder', 
                    'shaft', 
                    'activeStage' => [
                        'phase',
                        'parameters.parameter',
                        'operations.operation'
                    ],
                ],
                'currentOperation' => [
                    'engravingOrderShaft',
                    'operation.parameters.parameter'
                ],
                'operationsInWorkShift' => [
                    'engravingOrderShaft' => [
                        'shaft',
                        'engravingOrder'
                    ],
                    'operation'
                ],
                'workCenter.secondaryOperations'
            ]),
            'workShift' => WorkShiftCode::where('status', 'processing')->first(),
        ]);
            
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
