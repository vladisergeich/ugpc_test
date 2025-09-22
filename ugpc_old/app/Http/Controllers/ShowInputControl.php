<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ugpc\WorkShiftCode;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\MovementOrder;
use App\Models\Ugpc\OperationLedgerEntry;
use Illuminate\Support\Facades\DB;

class ShowInputControl extends Controller
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
        $operator = Auth::user();

        $engravingOrders = MovementOrder::where('movement_orders.engraver', 'Данафлекс-НАНО')->where('completion_date','>=',now()->format('Y-m-d'))->with([
            'designOrder.engravingOrders.shaft',
            'designOrder.engravingOrders.design_order',
            'designOrder.macroOrder'
        ])
        ->whereHas('designOrder.engravingOrders', function ($query)  {
            $query->where('status','<>','завершен');
            $query->where('input_control','<>',true);
        })
        ->orderBy('completion_date')
        ->orderBy('priority')
        ->get();

        $operations = OperationLedgerEntry::with('user','engravingOrder.shaft','engravingOrder.design_order','operation')->where('machine_id',16)->where('work_shift_code',$workShiftCode->code)->get();

        return view('ugpc.inputcontrol',[
            'work_shift_code' => $workShiftCode,
            'operator' => $operator,
            'engraving_orders' => $engravingOrders,
            'operations' => $operations,
        ]);
    }

    public function acceptedOrder(Request $request)
    {
        $order = MovementOrder::find($request->order['id']);
        $order->update($request->order);

        return $order;
    }
}
