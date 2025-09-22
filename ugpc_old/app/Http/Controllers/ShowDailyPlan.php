<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\WorkShiftCode;
use App\Models\Ugpc\MovementOrder;
use App\Http\Resources\EngravingStagesResource;
use App\Models\Ugpc\EngravingOrderStage;
use App\Models\Ugpc\OperationLedgerEntry;
use App\Models\BDGP\Order;
use App\User;
use Carbon\Carbon;

class ShowDailyPlan extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $date = Carbon::now();
        $date->addDays(7);

        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $workShiftCodes = WorkShiftCode::where('post',true)->orWhere('open',true)->orderBy('date','desc')->orderBy('starting_time','desc')->get();  
        $users = User::where('department','Printed cylinder engraving area')->get();
        
        $routeMaps = EngravingOrderStage::with([
            'engravingOrder.shaft.lastOkvid',
            'engravingOrder.design_order',
            'engravingStages.planCopperPlating'
        ])
        ->whereHas('engravingOrder', function ($query) {
            $query->where('status','<>', 'завершен');
        })
        ->join('engraving_orders', 'engraving_orders.id', '=', 'engraving_order_stages.engraving_order_id')
        ->join('orders', 'orders.id', '=', 'engraving_orders.design_order_id')
        ->where('orders.prod_order','<>',null)
        ->where('orders.prod_order','<>','')
        ->join('movement_orders', function($join) use ($date) {
            $join->on('movement_orders.okvid_number', '=', 'orders.okvid_number')
                 ->where('movement_orders.engraver', 'Данафлекс-НАНО')
                 ->where('movement_orders.distributed', true)
                 ->where('movement_orders.technical_condition', '<>', 'Отменён')
                 ->where('movement_orders.completion_date', '<', $date)
                 ->where('movement_orders.status', 'Заказы заполнены и готовы в работу');
        })
        ->select('engraving_order_stages.*','engraving_orders.comment') // Основной селект только из главной таблицы
        ->groupBy('engraving_order_stages.id')
        ->orderBy('movement_orders.completion_date')
        ->orderBy('movement_orders.priority')
        ->orderBy('movement_orders.okvid_number')
        ->orderBy('engraving_orders.separation_number')
        ->orderBy('engraving_order_stages.stage_number')
        ->orderBy('engraving_order_stages.id')
        ->get();


        $operations = OperationLedgerEntry::where('engraving_order_id','<>',null)->where('work_shift_code',$workShiftCode->code)->orWhere('end_time',null)->with(['engravingOrder.shaft','engravingOrder.design_order','machine','operation','user','cutter','factDiameter','diameterDifference','thickness','stage','factHardness'])->orderBy('work_shift_code')->orderBy('id')->get();

        $result = collect();

        foreach ($routeMaps as $group) {

            $add_group_row = $result->where('group_row',true)->where('okvid_number',$group->engravingOrder->design_order->okvid_number)->first();
            
            if ($add_group_row == null) {
                $result->push([
                    'id' => $group->engravingOrder->design_order->id,
                    'okvid_number' => $group->engravingOrder->design_order->okvid_number,
                    'completion_date' => $group->engravingOrder->design_order->movementOrder->completion_date,
                    'order_number' => $group->engravingOrder->design_order->prod_order,
                    'comment' => $group->engravingOrder->design_order->comment,
                    'group_row' => true,
                ]);
            }

            $result->push($group);
            
        }

        $routeMaps = $result;

        return view('ugpc.dailyplan',[
            'route_maps' => $routeMaps,
            'work_shift_codes' => $workShiftCodes,
            'operations' => $operations,
            'users' => $users,
        ]);
    }

    public function getData(Request $request)
    {
        $operations = OperationLedgerEntry::where('engraving_order_id','<>',null)
        ->when('work_shift_code', function ($query) use ($request) {
              
            if (array_key_exists('workShift',$request['code'])) {
                return $query->where('work_shift_code',$request['code']['workShift']);
            }
                        
        })
        ->when('posting_date', function ($query) use ($request) {
              
            if (array_key_exists('dates',$request['code']) && $request['code']['dates'][0] != null && $request['code']['dates'][1] != null) {
                $workShiftCodes = WorkShiftCode::where('date','>=',$request['code']['dates'][0])->where('date','<=',$request['code']['dates'][1])->pluck('code');
                return $query->whereIn('work_shift_code',$workShiftCodes);
            }
                        
        })
        ->when('user_id', function ($query) use ($request) {
              
            if (array_key_exists('user',$request['code'])) {
                return $query->where('user_id',$request['code']['user']);
            }
                        
        })
        ->with(['engravingOrder.shaft','engravingOrder.design_order','machine','operation','workShift','user','cutter','factDiameter','diameterDifference','thickness','stage','factHardness'])
        ->orderBy('work_shift_code')->orderBy('id')->get();

        return $operations;
    }

    public function addComment(Request $request)
    {
        $order = Order::find($request->id);
        $order->comment = $request->comment;
        $order->save();

        return $order;
    }
}
