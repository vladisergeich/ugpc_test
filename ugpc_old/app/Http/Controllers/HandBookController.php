<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BDGP\FormatVal;
use App\Models\BDGP\Customer;
use App\Models\BDGP\ProductType;
use App\Models\BDGP\Shaft;
use App\Models\BDGP\OrderStream;
use App\Models\BDGP\Order;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\ShaftResource;
use App\Services\SOAP\SoapClient;
use Illuminate\Support\Facades\DB;
use App\Models\BDGP\SteelShaftApplicationComposition;
use App\Models\BDGP\SteelShaftApplication;

class HandBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $customers = Customer::all();

        $shaft_resources = ShaftResource::orderBy('edition_batch', 'desc')
        ->join('shafts', 'shafts.shaft_id', '=', 'shaft_resources.shaft_id')
        ->join('orders', 'orders.okvid_number', '=', 'shaft_resources.okvid_number')
        ->selectRaw('shaft_resources.batch_date, shaft_orders.okvid_number')
        ->get();

        $formats = FormatVal::orderBy('format', 'asc')->get();


        return view('ugpc.handbook.index', [
            'customers' => $customers,
            'formats' => $formats,
            'shaft_resources' => $shaft_resources,
        ]);
    }

    public function getHandBookDataGp(Request $request) 
    {

        $customer = Customer::where('id',$request->data)->first();
        $data = ProductType::where('customer',$customer->description)->get();

        return $data;
    }

    public function getHandBookDataVal(Request $request) 
    {
        if ($request->data != []) {
            $formats = FormatVal::whereIn('format',$request->data)->pluck('format');
        } else {
            $formats = FormatVal::all()->pluck('format');
        }

        $shafts = Shaft::leftJoin('shaft_orders', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')
        ->select(
            'shaft_orders.warehouse_place',
            'shafts.shaft_id',
            'shafts.shaft_format',
            'shafts.ff',
            'shafts.manufacture_date',
            'shafts.manufacturer',
            'shafts.note',
            'shafts.id',
            'shafts.free',
            'shafts.warehouse',
            'shafts.diameter',
            'shaft_orders.order_status',
            'shaft_orders.id as order_id' // Добавим id заказа для фильтрации в коллекции
        )
        ->whereIn('shafts.shaft_format', $formats)
        ->orderBy('shafts.shaft_id', 'asc')
        ->get();

        // Группировка коллекции по shaft_id
        $groupedShafts = $shafts->groupBy('shaft_id');

        // Фильтрация коллекции по максимальному id для каждого shaft_id
        $filteredShafts = $groupedShafts->map(function ($group) {
            return $group->sortByDesc('order_id')->first();
        })->values();
        
        $shafts_free = Shaft::whereIn('shafts.shaft_format',$formats)->where('shafts.free',1)->get();


        $shafts_not_free = Shaft::whereIn('shaft_format',$formats)->where('free',0)->get();

        $orders_format = Order::whereIn('format',$formats)->get();


        //$shaft_orders = ShaftOrder::whereIn('okvid_number',$orders_format->pluck('okvid_number'))->whereIn('shaft_id',$shafts_not_free->pluck('shaft_id'))->where('written_off',false)->pluck('okvid_number');

        $orders = ShaftOrder::whereIn('shaft_orders.okvid_number', $orders_format->pluck('okvid_number'))
        ->where('shaft_orders.shaft_id', '<>', 0)
        ->where('shaft_orders.written_off', false)
        ->join('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')
        ->join('orders', 'orders.okvid_number', '=', 'shaft_orders.okvid_number')
        ->with('shaft', 'order.macroOrder.lastOrders.streams.productType')
        ->leftJoin('shaft_resources', function ($query) {
            $query->on('shaft_resources.macro_id', '=', 'orders.upak_order')
                ->whereRaw('shaft_resources.id IN (
                    SELECT MAX(a2.id) 
                    FROM shaft_resources AS a2 
                    JOIN shaft_orders AS u2 ON u2.shaft_id = a2.shaft_id 
                    GROUP BY a2.macro_id
                )');
        })
        ->orderBy('orders.format', 'asc')
        ->orderBy('macro_orders.macro_id', 'asc')
        ->orderBy('orders.printing_date','desc')
        ->groupBy('orders.upak_order')
        ->join('macro_orders', 'macro_orders.macro_id', '=', 'orders.upak_order')
        ->selectRaw('
            shaft_resources.batch_date, 
            shaft_orders.okvid_number, 
            MAX(orders.printing_date) as printing_date,
            orders.format, 
            orders.upak_order, 
            macro_orders.description, 
            macro_orders.macro_id, 
            macro_orders.customer, 
            orders.engraving, 
            orders.request_engraving_date, 
            COUNT(orders.okvid_number) as count,
            CASE 
                WHEN COUNT(DISTINCT shafts.warehouse) = 1 
                THEN MAX(shafts.warehouse) 
                ELSE "Смешанный" 
            END as warehouse
        ')
        ->get();
     

        return [$filteredShafts,$shafts_free,$shafts_not_free,$orders];
    }

    public function getProductInfo(Request $request) 
    {
        $product_info = [];
        $orderstreams = OrderStream::where('gp_code',$request->gp)->get();

        foreach ($orderstreams as $orderstream) {
            $orders_product = Order::where('okvid_number',$orderstream->okvid_number)->first();
            array_push($product_info,[$orderstream,$orders_product]);
        }
        

        return $product_info;
    }

    public function changeDateOrders(Request $request) 
    {
        $orders_approved = Order::whereBetween('request_engraving_date',[$request->dates[0],$request->dates[1]])
        ->join('macro_orders', 'macro_orders.macro_id', '=', 'orders.upak_order')
        ->join('shaft_orders', 'shaft_orders.okvid_number', '=', 'orders.okvid_number')
        ->where('shaft_orders.lineature','<',70)
        ->whereIn('shaft_orders.corner',[0,2])
        ->get();
                
        return($orders_approved);
    }

    public function getShaftInfo(Request $request) 
    {
        $shaft_orders = ShaftOrder::where('shaft_id',$request->shaft)
        ->with('order')
        ->select(['*', DB::raw('writeoff_date IS NULL AS writeoff_date_null')])
        ->orderBy('writeoff_date_null')
        ->orderBy('writeoff_date')
        ->get();
        

        return $shaft_orders;
    }

    public function newShaft($document_number) 
    {
        $application = SteelShaftApplication::where('document_number',$document_number)->first();
        $shafts_list = SteelShaftApplicationComposition::where('document_number',$document_number)->get();
        
        foreach ($shafts_list as $shaft) {  
            
            if (Shaft::where('shaft_id',$shaft->shaft_id)->doesntExist()) {
                $new_shaft = New Shaft();
                $new_shaft->manufacturer = $application->vendor;
                $new_shaft->manufacture_date = $application->document_date;
                $new_shaft->ff = $shaft->ff;
                $new_shaft->shaft_format = $application->format;
                $new_shaft->shaft_id = $shaft->shaft_id;
                $new_shaft->free = 1;
                $new_shaft->written_off = 0;
                $new_shaft->save();

                return $new_shaft;
            }
        }
    
    }

    public function newFormat(Request $request) 
    {
        
        $new_format = New FormatVal();
        $new_format->format = $request->new_format;
        $new_format->save();
        
        return $new_format;
    }

    public function newCustomer(Request $request) 
    {
        
        if (Customer::where('description',$request->new_customer)->doesntExist()) {
      
            $new_customer = New Customer();
            $new_customer->description = $request->new_customer;
            $new_customer->hidden = false;
            $new_customer->save();
        }
        
        return $new_customer;
    }

    public function submitShaftNote(Request $request) 
    {
        $shaft = Shaft::find($request->shaft['id']);

        $shaft_order = ShaftOrder::where('shaft_id',$request->shaft['shaft_id'])->get()->last();
        if ($shaft_order) {
            $shaft_order->order_status = $request->shaft['warehouse'];
            $shaft_order->warehouse_place = $request->shaft['warehouse_place'];

            if ($request->shaft['free'] && $request->shaft['free'] != $shaft->free) {
                $shaft_order->writeoff_date = date('Y-m-d');
                $shaft_order->written_off = true;
            }
            
            $shaft_order->save();
        }
        

        
        $shaft->note = $request->shaft['note'];
        $shaft->shaft_format = $request->shaft['shaft_format'];
        $shaft->free = $request->shaft['free'];
        $shaft->warehouse = $request->shaft['warehouse'];
        $shaft->warehouse_place = $request->shaft['warehouse_place'];
        $shaft->save();

        

        return($shaft_order);
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
