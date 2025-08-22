<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EngravingOrder,EngravingOrderShaft,Phase,OperationLedgerEntry,MachineCenter,WorkShiftCode};
use Inertia\Inertia;

class DailyPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function tablePlan()
    {
        $phases = Phase::with('engravingOrderShaft.engravingOrder','engravingOrderShaft.shaft','stages.operation')
        ->whereHas('engravingOrderShaft', function($query) {
            $query->where('status', 'in_progress');
        })
        ->orderBy('engraving_order_shaft_id')
        ->orderBy('sequence')->get();
        
        $result = collect();

        foreach ($phases as $group) {

            $add_group_row = $result->where('group_row',true)->where('okvid_number',$group->engravingOrderShaft->engravingOrder->okvid_number)->first();
            
            if ($add_group_row == null) {
                $result->push([
                    'id' => $group->engravingOrderShaft->engravingOrder->id,
                    'okvid_number' => $group->engravingOrderShaft->engravingOrder->okvid_number,
                    'group_row' => true,
                ]);
            }

            $result->push($group);
            
        }

        $phases = $result;

        return Inertia::render('DailyPlan/Index', [
            'activeTab' => 'tablePlan',
            'phases' => $phases, // Пагинация для снижения нагрузки
        ]);
    }

    public function statistic(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
    
        $workShiftCodes = WorkShiftCode::query()
            ->when($startDate && $endDate, fn($q) =>
                $q->whereDate('date', '>=', $startDate)
                  ->whereDate('date', '<=', $endDate),
                fn($q) => $q->where('status', 'processing')
            )
            ->pluck('id');
    
        $machines = MachineCenter::with([
            'completeOperations' => fn($q) =>
                $q->whereIn('work_shift_code_id', $workShiftCodes)->whereNotNull('engraving_order_shaft_id'),
            'completeOperations.operation',
            'completeOperations.engravingOrderShaft.shaft',
            'currentShaft.engravingOrderShaft.shaft'
        ])
        ->whereHas('completeOperations', fn($q) =>
            $q->whereIn('work_shift_code_id', $workShiftCodes)->whereNotNull('engraving_order_shaft_id')
        )
        ->get();
    
        return Inertia::render('DailyPlan/Index', [
            'activeTab' => 'statistic',
            'machines' => $machines,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
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
    public function destroy(string $id)
    {
        //
    }
}
