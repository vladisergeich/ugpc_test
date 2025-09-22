<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{OperationLedgerEntry,WorkShiftCode,MachineCenter, ShaftConsumption, OperationLedgerEntryParameter};
use App\Services\EngravingOrderService;

class OperationLedgerEntryController extends Controller
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

        $newOperation = OperationLedgerEntry::create([
            'engraving_order_shaft_id' => $request->engravingOrderShaft['id'] ?? null,
            'phase_stage_id' => $request->engravingOrderShaft['active_stage']['id'] ?? null,
            'operation_id' => $request->operationId,
            'starting_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
            'user_id' => $user->id,
            'work_shift_code_id' => $workShift->id,
            'machine_id' => $request->machineId,
            'status' => OperationLedgerEntry::STATUS_IN_PROGRESS,
        ]);

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
    public function update(Request $request)
    {
        $workShift = WorkShiftCode::where('status','processing')->first();
        $operation = OperationLedgerEntry::with('phaseStage.engravingOrderShaft')->find($request->operation['id']);

        foreach ($request->operation['operation']['parameters'] as $param) {
            OperationLedgerEntryParameter::updateOrCreate(
                ['parameter_id' => $param['parameter_id'], 'operation_ledger_entry_id' => $request->operation['id']],
                [
                    'float_value' => ($param['parameter']['type'] === 'number') ? $param['value'] : null,
                    'string_value' => ($param['parameter']['type'] === 'string') ? $param['value'] : null,
                ]
            );
        }

        $operation = OperationLedgerEntry::updateOrCreate(
            ['id' => $operation->id],
            [   'ending_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
                'work_shift_code' => $workShift->code,
                'status' => OperationLedgerEntry::STATUS_COMPLETED,
            ],
        );

        if ($operation->stage_id && ($operation->phaseStage->stage_id == 3 || ($operation->phaseStage->stage_id == 7 && $request->preCopper))) {
            app(EngravingOrderService::class)->createStages($operation->phaseStage->engravingOrderShaft, $operation->phaseStage,$request->preCopper);
        }

        if ($operation->status == 'completed' && $operation->phaseStage) {
            $operation->phaseStage->moveToNextStage();
        }

        ShaftConsumption::where('machine_center_id', $request->machineId)->delete(); 
        
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
                    'parameters.parameter',
                    'operations.operation'
                ],
            ],
        ])->find($request->machineId);

        return back()->with([
            'machine' => $machine,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        OperationLedgerEntry::find($request->operationId)->delete();

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
