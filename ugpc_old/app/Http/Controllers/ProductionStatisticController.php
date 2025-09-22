<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\OperationLedgerEntry;
use App\Models\Ugpc\ExecutionPlan;
use App\Models\Ugpc\MachineCenter;
use App\Models\Ugpc\WorkCenter;
use App\Models\Ugpc\Operation;
use App\Models\Ugpc\WorkShiftCode;
use App\Services\ProductionStatisticService;
use App\User;

class ProductionStatisticController extends Controller
{
    protected $productionStatisticService;

    // Внедрение зависимости через конструктор
    public function __construct(ProductionStatisticService $productionStatisticService)
    {
        $this->productionStatisticService = $productionStatisticService;
    }

    public function index()
    {
        return view('ugpc.statistic.index');
    }

    public function getData(Request $request)
    {
        $workShiftCodes = WorkShiftCode::where('date','>=',json_decode($request->filters)->dates[0])->where('date','<=',json_decode($request->filters)->dates[1])->pluck('code');

        return response()->json([
            'users' => User::where('department','Printed cylinder engraving area')->get(),
            'plan' => ExecutionPlan::where('engraver_id',5)->whereBetween('start_date', json_decode($request->filters)->dates)->get(),
            'data' => OperationLedgerEntry::with('engravingOrder','operation','user','thickness','machine')->join('operations','operations.id','operation_ledger_entries.operation_id')->join('work_shift_codes','work_shift_codes.code','operation_ledger_entries.work_shift_code')->select('operations.id','operations.type','operation_ledger_entries.*','work_shift_codes.code','work_shift_codes.date','work_shift_codes.letter')->whereIn('operation_ledger_entries.work_shift_code',$workShiftCodes)->where('engraving_order_id','<>',null)->where('end_time','<>',null)->get(),
        ]);
    }

    public function getWorkCenters(Request $request)
    {
        return response()->json([
            'workCenters' => WorkCenter::with('machines.workCenter.mainOperation','mainOperation')->get(),
        ]);
    }

    public function getOperations(Request $request)
    {
        $workCenters = MachineCenter::whereIn('machine',$request->machines)->pluck('work_center');
        return response()->json([
            'operations' => Operation::whereIn('work_center',$workCenters)->where('type','main')->get(),
        ]);
    }

    public function getOperatorData(Request $request)
    {
        $machines = MachineCenter::whereIn('machine',$request->machines)->pluck('id');
        $workShiftCodes = WorkShiftCode::where('date','>=',json_decode($request->filters)->dates[0])->where('date','<=',json_decode($request->filters)->dates[1])->pluck('code');

        $operations = OperationLedgerEntry::with('engravingOrder','operation','user','thickness')->join('operations','operations.id','operation_ledger_entries.operation_id')->join('work_shift_codes','work_shift_codes.code','operation_ledger_entries.work_shift_code')->select('operations.id','operations.type','operation_ledger_entries.*','work_shift_codes.code','work_shift_codes.letter')->whereIn('machine_id',$machines)->whereIn('operation_id',$request->operations)->whereIn('operation_ledger_entries.work_shift_code',$workShiftCodes)->where('engraving_order_id','<>',null)->where('end_time','<>',null)->get();
        
        $data = $this->productionStatisticService->aggregateOperatorsData($operations);

        return $data;
    }

    public function getShiftData(Request $request)
    {
        $machines = MachineCenter::whereIn('machine',$request->machines)->pluck('id');
        $workShiftCodes = WorkShiftCode::where('date','>=',json_decode($request->filters)->dates[0])->where('date','<=',json_decode($request->filters)->dates[1])->pluck('code');

        $operations = OperationLedgerEntry::with('engravingOrder','operation','user','thickness')->join('operations','operations.id','operation_ledger_entries.operation_id')->join('work_shift_codes','work_shift_codes.code','operation_ledger_entries.work_shift_code')->select('operations.id','operations.type','operation_ledger_entries.*','work_shift_codes.code','work_shift_codes.letter')->whereIn('machine_id',$machines)->whereIn('operation_id',$request->operations)->whereIn('operation_ledger_entries.work_shift_code',$workShiftCodes)->where('engraving_order_id','<>',null)->where('end_time','<>',null)->get();
        
        $data = $this->productionStatisticService->aggregateShiftsData($operations);

        return $data;
    }

}
