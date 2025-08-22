<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ShaftConsumption, EngravingOrder, MachineCenter};

class ShaftConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        // Проверяем наличие записи с таким же machine_center_id и engraving_order_id
        if (ShaftConsumption::where('machine_center_id', $request->machineId)
                            ->exists()) {
            // Возвращаем ошибку сессии
            return back()->withErrors([
                'error' => 'Машина занята',
            ]);
        }
    
        // Создаем новую запись
        ShaftConsumption::create([
            'engraving_order_shaft_id' => $request->engravingOrderShaftId,
            'machine_center_id' => $request->machineId,
        ]);
    
        // Загружаем данные машины с необходимыми отношениями
        $machine = MachineCenter::with([
            'shaftsInWarehouse.phase.engravingOrderShaft.engravingOrder',
            'shaftsInWarehouse.phase.engravingOrderShaft.shaft',
            'consumpShaft.engravingOrderShaft' => [
                'engravingOrder', 
                'shaft', 
                'activeStage.parameters.parameter'
            ],
        ])->find($request->machineId);
    
        // Возвращаем данные о машине и успешное сообщение в сессию
        return back()->with([
            'machine' => $machine,
            'message' => 'Вал потреблен',
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
        ShaftConsumption::find($request->consumpId)->delete();

        $machine = MachineCenter::with([
            'shaftsInWarehouse.phase.engravingOrderShaft.engravingOrder',
            'shaftsInWarehouse.phase.engravingOrderShaft.shaft',
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
