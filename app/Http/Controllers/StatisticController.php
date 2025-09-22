<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationLedgerEntry;
use App\Models\ExecutionPlan;
use App\Models\MachineCenter;
use App\Models\WorkCenter;
use App\Models\Operation;
use App\Models\WorkShiftCode;
use App\Services\StatisticService;
use App\Models\User;
use Inertia\Inertia;

class StatisticController extends Controller
{
    protected $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    public function index()
    {
        return Inertia::render('Statistic/Index');
    }

    public function getData(Request $request)
    {
        $filters = json_decode($request->filters, true);
        $workShiftCodes = WorkShiftCode::whereBetween('date', $filters['dates'])->pluck('code');

        return response()->json([
            'users' => User::where('department', 'Printed cylinder engraving area')->get(),
            'plan' => ExecutionPlan::where('engraver_id', 5)->whereBetween('start_date', $filters['dates'])->get(),
            'data' => OperationLedgerEntry::with('engravingOrder', 'operation', 'user', 'thickness', 'machine')
                ->join('operations', 'operations.id', 'operation_ledger_entries.operation_id')
                ->join('work_shift_codes', 'work_shift_codes.code', 'operation_ledger_entries.work_shift_code')
                ->select('operations.id', 'operations.type', 'operation_ledger_entries.*', 'work_shift_codes.code', 'work_shift_codes.date', 'work_shift_codes.letter')
                ->whereIn('operation_ledger_entries.work_shift_code', $workShiftCodes)
                ->whereNotNull('engraving_order_id')
                ->whereNotNull('end_time')
                ->get(),
        ]);
    }

    public function getWorkCenters(Request $request)
    {
        return response()->json([
            'workCenters' => WorkCenter::with('machines.workCenter.mainOperation', 'mainOperation')->get(),
        ]);
    }

    public function getOperations(Request $request)
    {
        $workCenters = MachineCenter::whereIn('machine', $request->machines)->pluck('work_center');
        
        return response()->json([
            'operations' => Operation::whereIn('work_center', $workCenters)->where('type', 'main')->get(),
        ]);
    }

    public function getOperatorData(Request $request)
    {
        $filters = json_decode($request->filters, true);
        $machines = MachineCenter::whereIn('machine', $request->machines)->pluck('id');
        $workShiftCodes = WorkShiftCode::whereBetween('date', $filters['dates'])->pluck('code');

        $operations = OperationLedgerEntry::with('engravingOrder', 'operation', 'user', 'thickness')
            ->join('operations', 'operations.id', 'operation_ledger_entries.operation_id')
            ->join('work_shift_codes', 'work_shift_codes.code', 'operation_ledger_entries.work_shift_code')
            ->select('operations.id', 'operations.type', 'operation_ledger_entries.*', 'work_shift_codes.code', 'work_shift_codes.letter')
            ->whereIn('machine_id', $machines)
            ->whereIn('operation_id', $request->operations)
            ->whereIn('operation_ledger_entries.work_shift_code', $workShiftCodes)
            ->whereNotNull('engraving_order_id')
            ->whereNotNull('end_time')
            ->get();
        
        $data = $this->statisticService->aggregateOperatorsData($operations);

        return $data;
    }

    public function getShiftData(Request $request)
    {
        $filters = json_decode($request->filters, true);
        $machines = MachineCenter::whereIn('machine', $request->machines)->pluck('id');
        $workShiftCodes = WorkShiftCode::whereBetween('date', $filters['dates'])->pluck('code');

        $operations = OperationLedgerEntry::with('engravingOrder', 'operation', 'user', 'thickness')
            ->join('operations', 'operations.id', 'operation_ledger_entries.operation_id')
            ->join('work_shift_codes', 'work_shift_codes.code', 'operation_ledger_entries.work_shift_code')
            ->select('operations.id', 'operations.type', 'operation_ledger_entries.*', 'work_shift_codes.code', 'work_shift_codes.letter')
            ->whereIn('machine_id', $machines)
            ->whereIn('operation_id', $request->operations)
            ->whereIn('operation_ledger_entries.work_shift_code', $workShiftCodes)
            ->whereNotNull('engraving_order_id')
            ->whereNotNull('end_time')
            ->get();
        
        $data = $this->statisticService->aggregateShiftsData($operations);

        return $data;
    }
}