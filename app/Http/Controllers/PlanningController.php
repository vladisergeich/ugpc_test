<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BDGP\Order;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\Shaft;
use App\Models\Ugpc\ShaftRequestComposition;
use App\Models\Ugpc\MovementOrder;
use App\Models\Ugpc\GlobalData;
use App\Models\Ugpc\ExecutionPlan;
use App\Models\BDGP\OrderState;
use App\Models\BDGP\ShaftOrder;
use App\Models\Ugpc\RouteMapCard;
use App\Models\BDGP\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function movementOrders(Request $request) 
    {
        $currentMonth = Carbon::now()->format('m.Y');
        
        $execution_plan = ExecutionPlan::where('month',$currentMonth)->get();
        $engravers = GlobalData::where('variable_name','engraver')->pluck('variable_body');
        $movement_novizna = GlobalData::where('variable_name','movement_novizna')->pluck('variable_desc');
        $downtimes = GlobalData::where('variable_name','movement_downtime')->pluck('variable_desc');
        $conditions = OrderState::pluck('order_state');
        $customers = Customer::pluck('description');

        $movement_orders = MovementOrder::where('distributed', true)->where('deleted',false)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = orders.cylinder_quantity))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        $movement_statuses = GlobalData::where('variable_name','movement_order_status')->pluck('variable_desc');


        return view('ugpc.planning.movement_orders',[
            'movement_orders' => $movement_orders,
            'engravers' => $engravers,
            'movement_novizna' => $movement_novizna,
            'conditions' => $conditions,
            'movement_statuses' => $movement_statuses,
            'downtimes' => $downtimes,
            'customers' => $customers,
            'execution_plan' => $execution_plan,
            
        ]);
    }

    public function dailyDistribution(Request $request) 
    {
        $startDate = Carbon::parse($request->params['date_start']);
        $endDate = Carbon::parse($request->params['date_end']);
        $currentDate = clone $startDate;
        $totalDailyValue = 0;
    
        $movement_orders = MovementOrder::where('distributed',true)->where('post',false)->where('engraver',$request->params['engraver'])->where('completion_date','>=',$request->params['date_start'])->where('completion_date','<=',$request->params['date_end'])->where('order_number','<>',null)->orderBy('printing_date', 'asc')->orderBy('completion_date', 'asc')->get();

        foreach ($movement_orders as $row) {
            if ($currentDate > $endDate) {
                break;
            }
    
            $rowValue = $row->shaft_quantity; 
    
            if ($totalDailyValue + $rowValue <= $request->params['qty']) {
                $max_priority = MovementOrder::where('distributed',true)->where('post',false)->where('completion_date',$currentDate)->where('engraver',$request->params['engraver'])->where('order_number','<>',null)->max('priority');

                $row->completion_date = $currentDate;
                $row->priority = $max_priority+1;
                $row->save();
                $totalDailyValue += $rowValue;
            } else {

                $currentDate->addDays(1);

                $max_priority = MovementOrder::where('distributed',true)->where('post',false)->where('completion_date',$currentDate)->where('engraver',$request->params['engraver'])->where('order_number','<>',null)->max('priority');

                $totalDailyValue = 0;
                $row->completion_date = $currentDate;
                $row->priority = $max_priority+1;
                $row->save();
                $totalDailyValue += $rowValue;
            }
        }

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();

        

        return $movement_orders;

    }

    public function postMovementOrders(Request $request) 
    {
        $date = Carbon::parse($request->params['date'])->format('Y-m-d');

        $movement_orders = MovementOrder::where('distributed',true)->where('completion_date',$date)->where('engraver',$request->params['engraver'])->get();

        foreach ($movement_orders as $order) {
            $order->post = true;
            $order->save();
        }

        $emails = [
            'm.tinchurina@danaflex.ru',
            'd.biktimirov@danaflex.ru',
            'a.maksytov@danaflex.ru',
            'v.arsentev@danaflex.ru',
        ];

        $movement_orders = MovementOrder::where('movement_orders.distributed',true)->where('movement_orders.completion_date',$date)->where('movement_orders.engraver',$request->params['engraver'])
        ->leftJoin('shaft_orders','shaft_orders.okvid_number','movement_orders.okvid_number')
        ->leftJoin('shafts','shafts.shaft_id','shaft_orders.shaft_id')
        ->where('shaft_orders.shaft_id','<>',0)
        ->get();

        if ($movement_orders != null) {
            foreach ($emails as $email) {
                Mail::send('ugpc/mail_schedule_confirm', ['movement_orders' => $movement_orders, 'request' => $request], function($message) use ($email, $request) {
                    $message->to($email)->subject("График на " . $request->params['date']. " число");
                    $message->from('d.portal@danaflex.ru', 'UGPC-Portal');
                });
            }
        }

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }
        
    public function cancelChanges(Request $request) 
    {
        $movement_orders = MovementOrder::where('distributed',true)->where('completion_date',$request->params['date'])->where('engraver',$request->params['engraver'])->get();

        foreach ($movement_orders as $order) {
            $order->post = false;
            $order->save();
        }

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }

    public function deleteRow(Request $request) 
    {
        $row = MovementOrder::where('okvid_number',$request['okvid_number'])->where('order_number',$request['order_number'])->where('completion_date',$request['completion_date'])->first();
        $row->distributed = false;
        $row->save();

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }

    
    public function unloadingOrders(Request $request) 
    {
        $movement_novizna = GlobalData::where('variable_name','movement_novizna')->pluck('variable_desc');
        $customers = Customer::pluck('description');
        $engravers = GlobalData::where('variable_name','engraver')->pluck('variable_body');
        $conditions = OrderState::pluck('order_state');
        $unloading_orders = MovementOrder::where('distributed',false)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->where('novizna','<>','Допечатная подготовка')
        ->join('orders','orders.okvid_number','movement_orders.okvid_number')
        ->select('orders.order_accepted_date','movement_orders.*', DB::raw('false as editing'),DB::raw('false as selectHover'))
        ->where('post',false)->orderBy('id','desc')->get();
        $movement_statuses = GlobalData::where('variable_name','movement_order_status')->pluck('variable_desc');


        return view('ugpc.planning.unloading_orders',[
            'unloading_orders' => $unloading_orders,
            'engravers' => $engravers,
            'conditions' => $conditions,
            'movement_statuses' => $movement_statuses,
            'movement_novizna' => $movement_novizna,
            'customers' => $customers,
        ]);
    }

    public function savePlan(Request $request) 
    {
        $currentMonth = Carbon::now()->format('m.Y');

        $execution_plan = ExecutionPlan::where('engraver',$request->engraver)->where('month',$currentMonth)->firts();
        $execution_plan->plan_qty = $request->plan;
        $execution_plan->save();

        $execution_plan = ExecutionPlan::where('month',$currentMonth)->get();
        
        return $execution_plan;
    }



    public function saveMovementData(Request $request) 
    {       
        $unloading_order = MovementOrder::find($request['id']);
        $unloading_order->update($request->all());

        $unloading_orders = MovementOrder::where('distributed',false)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->where('novizna','<>','Допечатная подготовка')
        ->join('orders','orders.okvid_number','movement_orders.okvid_number')
        ->select('orders.order_accepted_date','movement_orders.*', DB::raw('false as editing'),DB::raw('false as selectHover'))
        ->where('post',false)->orderBy('id','desc')->get();
        
        return $unloading_orders;
    }

    public function saveMovementDataRow(Request $request) 
    {
        $movement_order = MovementOrder::find($request['id']);
        $movement_order->update($request->all());

        if ($movement_order->removed == true) {
            $movement_order->status = 'Снятые заказы' ;
            $movement_order->save();
        }
            
        
        return 'ok';
    }

    public function addDowntime(Request $request) 
    {
        $new_downtime = new MovementOrder();
        $new_downtime->status = 'Простой';
        $new_downtime->order_number = '';
        $new_downtime->okvid_number = '';
        $new_downtime->description = $request->params['type'].' ('.$request->params['qty'].'часов )';
        $new_downtime->completion_date = $request->params['date'];
        $new_downtime->priority = 0;
        $new_downtime->distributed = true;
        $new_downtime->engraver = $request->params['engraver'];
        $new_downtime->save();

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }

    public function deleteDowntime(Request $request) 
    {
        MovementOrder::where('id',$request->record['id'])->delete();

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }

    public function saveMovementDataPriority(Request $request) 
    {
        $movementData = $request->all();

        foreach ($movementData as $data) {
            if (array_key_exists('order_number', $data)) {
                //$record = MovementOrder::where('stage_engraving_order',$data['stage_engraving_order'])->where('order_number',$data['order_number'])->where('okvid_number',$data['okvid_number'])->where('distributed',true)->first();
                $record = MovementOrder::where('id',$data['id'])->first();
                if ($record) {
                    $record->priority = $data['priority'];
                    $record->completion_date = $data['completion_date'];
                    $record->save();
                }
            }
        }

        return $movementData;
    }

    public function distributeOrders(Request $request) 
    {
        $distribute_orders = MovementOrder::where('distributed',false)->where('post',false)->where('completion_date',$request->distributeDate)->where('engraver',$request->distributeArea)->get();

        $max_priority = MovementOrder::where('distributed',true)->where('post',false)->where('completion_date',$request->distributeDate)->where('engraver',$request->distributeArea)->max('priority');
        foreach ($distribute_orders as $order) {
            $order->distributed = true;
            $order->priority = $max_priority+1;
            $order->save();
        }
        $unloading_orders = MovementOrder::where('distributed',false)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->where('novizna','<>','Допечатная подготовка')
        ->join('orders','orders.okvid_number','movement_orders.okvid_number')
        ->select('orders.order_accepted_date','movement_orders.*', DB::raw('false as editing'),DB::raw('false as selectHover'))
        ->where('post',false)->orderBy('id','desc')->get();
        
        return $unloading_orders;
    }

    public function breakRow(Request $request) 
    {
        $row = MovementOrder::with('designOrder.finishedShafts')->where('id',$request->params['row']['id'])->first();

        if ($row->stage_engraving_order != null) {
            $stage_engraving_order = $stage_engraving_order->stage_engraving_order;
        } else {
            $stage_engraving_order = 1;
        }

        $new_row_first = $row->replicate();
        $new_row_first->completion_date = $request->params['first_date'];
        $new_row_first->shaft_quantity = $request->params['first_qty'];
        $new_row_first->stage_engraving_order = $stage_engraving_order;
        $new_row_first->shaft_quantity_fact = $row->designOrder->finishedShafts->count();
    


        $new_row_second = $row->replicate();
        $new_row_second->completion_date = $request->params['second_date'];
        $new_row_second->shaft_quantity = $request->params['second_qty'];
        $new_row_second->stage_engraving_order = $stage_engraving_order+1;
        $new_row_second->shaft_quantity_fact = null;

        $row = MovementOrder::where('distributed',true)->where('okvid_number',$request->params['row']['okvid_number'])->where('order_number',$request->params['row']['order_number'])->delete();

        $new_row_first->save();
        $new_row_second->save();

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }

    public function backBreakrow(Request $request) 
    {
        $firstRow = MovementOrder::with('designOrder.finishedShafts')->where('distributed',true)->where('okvid_number',$request->params['row']['okvid_number'])->first();
        $firstRow->shaft_quantity = $firstRow->designOrder->cylinder_quantity;
        $firstRow->stage_engraving_order = null;
        $firstRow->shaft_quantity_fact = $firstRow->designOrder->finishedShafts->count();
        $firstRow->save();

        MovementOrder::where('distributed',true)->where('okvid_number',$request->params['row']['okvid_number'])->where('id','<>',$firstRow->id)->delete();

        $movement_orders = MovementOrder::where('distributed', true)
        ->where(function ($query) {
            $query->where('order_number','<>',null)
                  ->where('order_number','<>','')
                  ->orWhere('status','Простой');
        })
        ->leftJoin('shaft_requests', 'shaft_requests.document_number', 'movement_orders.transfer_document')
        ->leftJoin('orders', 'orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('global_data', 'global_data.variable_desc', 'movement_orders.status')
        ->leftJoin('shaft_orders', 'shaft_orders.okvid_number', 'movement_orders.okvid_number')
        ->leftJoin('shafts', 'shafts.shaft_id', 'shaft_orders.shaft_id')
        ->select(
            'global_data.*',
            'orders.*',
            'movement_orders.*',
            'shaft_requests.shipping_date',
            DB::raw('(
                CASE 
                    WHEN (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) = SUM(CASE WHEN shafts.warehouse = movement_orders.engraver THEN 1 ELSE 0 END) && (COUNT(CASE WHEN shafts.shaft_id <> 0 THEN shaft_orders.okvid_number END) != 0))
                    THEN true 
                    ELSE false 
                END
            ) AS shafts_in_warehouse')
        )
        ->orderBy('completion_date')
        ->groupBy('movement_orders.completion_date','movement_orders.okvid_number', 'movement_orders.stage_engraving_order')
        ->orderBy('priority')
        ->get();
        
        return $movement_orders;
    }


    public function okvidFulfilled(Request $request) 
    {

        $okvid_fulfilled = Order::find($request->okvid_number['id']);
        $okvid_fulfilled->update($request->okvid_number);
           
        return($okvid_fulfilled);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
