<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{DefectLedgerEntry,Shaft, EngravingOrderShaft,OperationLedgerEntry,WorkShiftCode,ShaftConsumption,MachineCenter,PhaseStage,Phase};
use Illuminate\Support\Facades\Auth;

class DefectLedgerEntryController extends Controller
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
        $user = Auth::user();
        $workShift = WorkShiftCode::where('status','processing')->first();

        $defect = DefectLedgerEntry::updateOrCreate(
            ['engraving_order_shaft_id' => $request->machine['consump_shaft']['engraving_order_shaft']['id'], 'phase_stage_id' => $request->machine['consump_shaft']['engraving_order_shaft']['active_stage']['id']],
            ['user_id' => $user->id, 'work_shift_code_id' => $workShift->id, 'machine_id' => $request->machine['id'], 'reason_id' => $request->defect['reason_id'] ?? null, 'note' => $request->defect['note'] ?? null]
        );

        OperationLedgerEntry::where('id', $request->machine['current_operation']['id'] ?? null)->delete(); 
        ShaftConsumption::where('machine_center_id', $request->machine['id'])->delete(); 

        $stage = PhaseStage::updateOrCreate(
            ['id' => $request->machine['consump_shaft']['engraving_order_shaft']['active_stage']['id']],
            ['status' => 'rejected']
        );

        $phase = Phase::updateOrCreate(
            ['id' => $request->machine['consump_shaft']['engraving_order_shaft']['active_stage']['phase']['id']],
            ['status' => 'rejected']
        );

        $shaft = Shaft::updateOrCreate(
            ['id' => $request->machine['consump_shaft']['engraving_order_shaft']['shaft_id']],
            ['status' => 'rejected']
        );

        $machine = MachineCenter::with([
            'shaftsInWarehouse.phase.engravingOrderShaft.engravingOrder',
            'shaftsInWarehouse.phase.engravingOrderShaft.shaft',
            'operationsInWorkShift' => [
                'engravingOrderShaft',
                'operation'
            ],
            'currentOperation' => [
                'engravingOrderShaft',
                'operation.parameters.parameter'
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
        ])->find($request->machine['id']);
    
        // Возвращаем данные о машине и успешное сообщение в сессию
        return back()->with([
            'machine' => $machine,
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
        DefectLedgerEntry::find($request->defectId)->delete();

        $machine = MachineCenter::with([
            'shaftsInWarehouse.phase.engravingOrderShaft.engravingOrder',
            'shaftsInWarehouse.phase.engravingOrderShaft.shaft',
            'operationsInWorkShift' => [
                'engravingOrderShaft',
                'operation'
            ],
            'currentOperation' => [
                'engravingOrderShaft',
                'operation'
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
        ])->find($request->machineId);

        // Возвращаем данные о машине и успешное сообщение в сессию
        return back()->with([
            'machine' => $machine,
        ]);
    }
}
