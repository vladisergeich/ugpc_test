<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\WorkShiftCode;
use App\Http\Resources\EngravingStagesResource;
use App\Models\Ugpc\EngravingOrderStage;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\OperationLedgerEntry;
use App\Models\Ugpc\MachineCenter;
use App\Models\Ugpc\Operation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShowElectroPlating extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $machines = MachineCenter::where('work_center','EP')->groupBy('machine')->get();
        $secondaryOperation = Operation::where('type','secondary')->where('work_center','EP')->get();
        $operations = OperationLedgerEntry::where('work_center_id',1)->where('work_shift_code',$workShiftCode->code)->with(['engravingOrder.shaft','engravingOrder.design_order','machine','operation','user','cutter','factDiameter','diameterDifference','thickness','stage'])->orderBy('end_time')->get();

        return view('ugpc.electroplating',[
            'work_shift_code' => $workShiftCode,
            'operations' => $operations,
            'machines' => $machines,
            'secondary_operation' => $secondaryOperation,
        ]);
    }

    public function postSecondaryOperation(Request $request)
    {
        $user = Auth::user();
        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $operation = Operation::find($request->operationId);

        $newOperation = OperationLedgerEntry::create([
            'start_time' => Carbon::parse($request->startTime)->setTimezone('Europe/Moscow')->format('H:i:s'),
            'end_time' => Carbon::parse($request->endTime)->setTimezone('Europe/Moscow')->format('H:i:s'),
            'operation_id' => $request->operationId,
            'posting_date' => now()->format('Y-m-d'),
            'work_shift_code' => $workShiftCode->code ?? null,
            'work_center_id' => 1,
            'machine_id' => $request->machineId ?? null,
            'user_id' => $user->id,
        ]);

        $operations = OperationLedgerEntry::with('machine','engravingOrder.shaft','engravingOrder.design_order','operation','user')->where('work_center_id',1)->where('work_shift_code',$workShiftCode->code)->orderBy('end_time')->get();

        return $operations;
    }
}
