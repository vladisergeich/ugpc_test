<?php

namespace App\Http\Controllers;

use Spatie\ArrayToXml\ArrayToXml;

use App\Models\Navision;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Http\SimpleXMLElement;
use Illuminate\Support\Facades\Auth;
use App\Models\BDGP\Customer;
use App\Models\BDGP\ProductType;
use App\Models\BDGP\Order;
use App\Models\BDGP\OrderStatus;
use App\Models\BDGP\OrderState;
use App\Models\BDGP\Profile;
use App\Models\BDGP\ColorBook;
use App\Models\BDGP\FormatVal;
use App\Models\BDGP\Designer;
use App\Models\BDGP\ManagerSupport;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\Shaft;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\OrderStream;
use App\Models\BDGP\LayoutConstructor;
use App\Models\BDGP\ShaftResource;
use App\Models\BDGP\ShaftRequest;
use App\Models\Ugpc\ShaftRequestComposition;
use App\Models\BDGP\Material;
use App\Models\Ugpc\RouteMapCard;
use App\Models\Ugpc\InputControl;
use App\Models\BDGP\MountingParameter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShipmentExport;
use Barryvdh\DomPDF;
use App;
use \Config;
use Barryvdh\DomPDF\PDF;
use Mockery\Undefined;
use PhpParser\Node\Expr\New_;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use App\Services\SOAP\SoapClient;

class OrderCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MacroOrder $macroorder)
    {

        $user = Auth::user();

        $customers = Customer::orderBy('description', 'asc')->get();
        $vendors = Vendor::all();
        $osz = ManagerSupport::orderBy('manager_fio', 'asc')->get();
        $designers = Designer::orderBy('fio', 'asc')->get();
        $formats = FormatVal::orderBy('format', 'asc')->get();
        $colors = ColorBook::orderBy('color_description', 'asc')->get();
        $mountig_parameters = MountingParameter::orderBy('description', 'asc')->get();
        $producttypes = ProductType::all();
        $shafts = Shaft::where('free',true)->orWhere('shaft_id',0)->get();
        $orderstatus = OrderStatus::all();
        $orderstate = OrderState::orderBy('order_state', 'asc')->get();
        $profile = Profile::orderBy('short_name', 'asc')->get();
        $orderstreams = OrderStream::all();
        $materials = Material::all();

        $shafts_excel = Shaft::where('shafts.shaft_id','<>',0)
        
        ->leftJoin('shaft_orders', function($query) {
            $query->on('shaft_orders.shaft_id','=','shafts.shaft_id')
                ->whereRaw('shaft_orders.id IN (select MAX(a2.id) from shaft_orders as a2 join shafts as u2 on u2.shaft_id = a2.shaft_id group by a2.shaft_id)');
        })
        ->selectRaw('shafts.shaft_id, shafts.shaft_format,shafts.ff,shaft_orders.okvid_number,shaft_orders.order_status,shaft_orders.warehouse_place,shafts.note,shafts.warehouse')
        ->get();
        
        
        $order = Order::where('upak_order',$macroorder->macro_id)->first();
        $last_request_order = ShaftRequestComposition::where('okvid_number',$order->okvid_number)->get()->last();
        if ($last_request_order == null) {
            $last_request_order = [0];
        }
        
        $orders_double = Order::where('upak_order',$macroorder->macro_id)->get();
        $shaftresourses = ShaftResource::where('okvid_number',$order->okvid_number)->get();
        $shaft_resources = ShaftResource::
        join('shafts', 'shafts.shaft_id', '=', 'shaft_resources.shaft_id')
        ->where('shafts.free',false)
        ->join('orders', 'orders.okvid_number', '=', 'shaft_resources.okvid_number')
        ->join('macro_orders', 'macro_orders.macro_id', '=', 'orders.upak_order')
        ->selectRaw('macro_orders.description,shaft_resources.okvid_number,shaft_resources.shaft_id,shaft_resources.edition_batch, SUM(shaft_resources.edition_batch)*100/2000000 as qty')->orderBy('qty', 'desc')->groupBy('shaft_resources.shaft_id')->get();

        $shaftorders = ShaftOrder::where('okvid_number',$order->okvid_number)->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')->select('shaft_orders.*','shafts.ff')->get();

        $shafts_resource = [];
        $shaftordersallokvid = ShaftOrder::whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->where('shaft_orders.shaft_id','<>',0)->where('shaft_orders.written_off',false)
        ->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')
        ->select('shafts.warehouse','shaft_orders.*','shafts.shaft_id')
        ->orderBy('shaft_order_number')->get();

        foreach ($shaftordersallokvid as $okvid) {

            $shaftresourse = ShaftResource::where('shaft_id',$okvid->shaft_id)->whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->sum('edition_batch');

            array_push($shafts_resource,[$okvid,$shaftresourse ?? 0]);
        }


        $orderstreams = OrderStream::where('okvid_number',$order->okvid_number)->get();

        $ordergp = [];
        foreach ($orderstreams as $orderstream) {

            $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
            
            if ($producttype != null) {
                array_push($ordergp,[$producttype,$orderstream]);
            }

        }

        $layoutconstructor = LayoutConstructor::where('okvid_number',$order->okvid_number)->get();


        return view('ugpc.bdgp.order_card', [
            'mountig_parameters' => $mountig_parameters,
            'customers' => $customers,
            'producttypes' => $producttypes, 
            'orderstatus' => $orderstatus,
            'orders' => $order,
            'shafts' => $shafts,
            'shafts_excel' => $shafts_excel,
            'orderstate' => $orderstate,
            'profile' => $profile,
            'colors' => $colors,
            'materials' => $materials,
            'formats' => $formats, 
            'designers' => $designers, 
            'osz' => $osz, 
            'vendors' => $vendors,
            'macroorder' => $macroorder,
            'shaftorders' => $shaftorders,
            'orderstreams' => $orderstreams,
            'layoutconstructor' => $layoutconstructor,
            'shaftresourses' => $shaftresourses,
            'ordergp' => $ordergp,
            'customers' => $customers,
            'macroorder' => $macroorder,
            'shaftordersallokvid' => $shaftordersallokvid,
            'user' => $user,
            'shafts_resource' => $shafts_resource,
            'last_request_order' => $last_request_order,
            'shaft_resources' => $shaft_resources,
        ]);
    }


    public function deleteShaft(Request $request)
    {
        $shaft = ShaftOrder::where('id',$request->shaftid)->delete();
        $shaft_free = Shaft::where('shaft_id',$request->shaftid)->first();
        $shaft_free->save();

        return ($shaft);
    }

    public function freeShaft(Request $request)
    {
        $shaft = ShaftOrder::where('shaft_id',$request->shaftid)->where('written_off',false)->first();

        $shaft->written_off = true;
        $shaft->writeoff_date = date('Y-m-d');
        $shaft->save();

        $shaft_resource = shaftResource::where('shaft_id',$request->shaftid)->delete();

        $shaft_free = Shaft::where('shaft_id',$request->shaftid)->first();
        $shaft_free->free = true;
        $shaft_free->save();

        return ($shaft);
    }

    public function backShaft(Request $request)
    {
        $shaft = ShaftOrder::where('shaft_id',$request->shaftid)->where('written_off',true)->get()->last();

        $shaft->written_off = false;
        $shaft->writeoff_date = null;
        $shaft->save();

        return ($shaft);
    }

    public function getShafts(Request $request) 
    {
        $data = ShaftOrder::where('okvid_number',$request->okvid)->get();

        return ($data);
    }

    public function deleteStream(Request $request)
    {
        $stream = LayoutConstructor::where('id',$request->stream)->delete();

        $data = LayoutConstructor::where('okvid_number',$request->okvid)->get();

        return ($data);
    }

    public function submitStream(Request $request) 
    {
        $stream = LayoutConstructor::find($request->stream['id']);
        $stream->update($request->stream);
           
        return($stream);
    }

    public function addStream(Request $request) 
    {
        $data = LayoutConstructor::where('okvid_number',$request->okvid)->count();

        $newStream = New LayoutConstructor();
        $newStream->stream_number = $data+1;
        $newStream->okvid_number = $request->okvid;
        $newStream->save();

        $data = LayoutConstructor::where('okvid_number',$request->okvid)->get();

        return ($data);
    }

    public function getLayout(Request $request) 
    {
        $data = LayoutConstructor::where('okvid_number',$request->okvid)->get();

        return ($data);
    }

    public function scPreset(Request $request) 
    {
        $order = Order::find($request->order_id);
        $order->condition = 'Папка в ОКВиД';
        $order->order_accepted_date = date('Y-m-d');
        $order->request_install_date = date('Y-m-d');
        $order->sleeve_lenght = 1370;
        $order->width_engraving = 1300;
        $order->winding_option = '40';
        $order->supplier = 'Данафлекс ЗАО';
        $order->save();
        return ($order);
    }

    public function autoApproval(Request $request) 
    {
        $order = Order::find($request->params['order']);

        $xml_order = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <dataroot xmlns:od="urn:schemas-microsoft-com:officedata" generated="2022-02-07T16:50:16">
        </dataroot>
        ');  
    
        $response = Http::attach('attachment',$xml_order->saveXML() , $order->prod_order.'_H'.$order->upak_order.'-'.substr($order->okvid_number, -2).'.xml ') ->post('http://10.10.40.94:4415/ws/approval/order');

        if ((mb_substr($order->prod_order,0,1) == 'A') || (mb_substr($order->prod_order,0,1) == 'a')){
            $place = 'navALABUGA';
        } else if ((mb_substr($order->prod_order,0,1) == 'N') || (mb_substr($order->prod_order,0,1) == 'n')) {
            $place = 'navNANO';
        } else {
            $place = 'navZAO';
        }

        switch ($place) {
            case 'navZAO':
                $url = 'http://10.10.40.107:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/ProdOrderApprovalPage';
                break;
            case 'navNANO':
                $url = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/ProdOrderApprovalPage';
                break;
            case 'navALABUGA':
                $url = 'http://10.5.128.144:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/ProdOrderApprovalPage';
                break;
            default:
                return 'error place code';
                break;
        }
        
        $options = [
            'login' => 'nav-port',
            'password' => 'Shambala12!'
        ];


        $client = new SoapClient($url,$options);
        $array = $client->ReadMultiple(
            array( 
                'filter'=>[ 
                    [
                    'Field' => 'Prod_Order_No',
                    'Criteria' => "'{$order->prod_order}'",
                    ],
                    [
                    'Field' => 'Approval Code',
                    'Criteria' => "1_КООРДИНТР_ВАЛЫ|1_КООРДИНТР_СТАЛЬ",
                    ],
                ],
                'setSize' => 100
            ) 
        )->{'ReadMultiple_Result'};

        if (property_exists($array, 'ProdOrderApprovalPage')) {
            if (is_array($array->ProdOrderApprovalPage)) {
                foreach ($array->ProdOrderApprovalPage as $element) {
                    $element->AutoApproval = true;
                }
            } else {
                $array->ProdOrderApprovalPage->AutoApproval = true;
            }

            $object_nav = new \stdClass;

            $object_nav->ProdOrderApprovalPage_List = $array;
    
            try {
                $client->UpdateMultiple($object_nav);
            } catch (Throwable $e) {
                
            }
        }

        return $xml_order;
    }

    public function rmPreset(Request $request) 
    {

        $order = Order::find($request->order_id);
        $order->condition = 'Папка в ОКВиД';
        $order->order_accepted_date = date('Y-m-d');
        $order->request_install_date = date('Y-m-d');
        $order->sleeve_lenght = 1400;
        $order->width_engraving = 1370;
        $order->winding_option = '40';
        $order->supplier = 'Данафлекс-НАНО';
        $order->save();
        return ($order);
    }

    public function nextOkvid(Request $request) 
    {
        if ($request->type == 'last') {
            $order_number = Order::where('okvid_number',$request->okvid)->first();
            $order = Order::where('upak_order',$order_number->upak_order)->get()->last();

            $layoutconstructor = LayoutConstructor::where('okvid_number',$order->okvid_number)->get();

            $shaftresourses = ShaftResource::where('okvid_number',$request->okvid)->get();

            $shaftorders = ShaftOrder::where('okvid_number',$order->okvid_number)->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')->select('shaft_orders.*','shafts.ff')->get();

            $orderstreams = OrderStream::where('okvid_number',$order->okvid_number)->get();

            $last_request_order = ShaftRequestComposition::where('okvid_number',$order->okvid_number)->get()->last();
            if ($last_request_order == null) {
                $last_request_order = [0];
            }

            {
            $ordergp = [];
            foreach ($orderstreams as $orderstream) {
    
                $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
                
                if ($producttype != null) {
                    array_push($ordergp,[$producttype,$orderstream]);
                }
    
            }

            $orders_double = Order::where('upak_order',$order->upak_order)->get();

            $shafts_resource = [];
            $shaftordersallokvid = ShaftOrder::whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->where('shaft_orders.shaft_id','<>',0)->where('shaft_orders.written_off',false)
            ->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')
            ->select('shaft_orders.*','shafts.warehouse','shafts.shaft_id')
            ->orderBy('shaft_order_number')->get();
            
            foreach ($shaftordersallokvid as $okvid) {
                $shaftresourse = ShaftResource::where('shaft_id',$okvid->shaft_id)->whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->sum('edition_batch');
                array_push($shafts_resource,[$okvid,$shaftresourse ?? 0]);
            }
        }
    
        }else if ($request->type == 'first') {
            $order_number = Order::where('okvid_number',$request->okvid)->first();
            $order = Order::where('upak_order',$order_number->upak_order)->first();

            $layoutconstructor = LayoutConstructor::where('okvid_number',$order->okvid_number)->get();

            $shaftresourses = ShaftResource::where('okvid_number',$request->okvid)->get();

            $shaftorders = ShaftOrder::where('okvid_number',$order->okvid_number)->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')->select('shaft_orders.*','shafts.ff')->get();

            $orderstreams = OrderStream::where('okvid_number',$order->okvid_number)->get();

            $last_request_order = ShaftRequestComposition::where('okvid_number',$order->okvid_number)->get()->last();
            if ($last_request_order == null) {
                $last_request_order = [0];
            }

            $ordergp = [];
            foreach ($orderstreams as $orderstream) {
    
                $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
                
                if ($producttype != null) {
                    array_push($ordergp,[$producttype,$orderstream]);
                }
    
            }

            $orders_double = Order::where('upak_order',$order->upak_order)->get();

            $shafts_resource = [];
            $shaftordersallokvid = ShaftOrder::whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->where('shaft_orders.shaft_id','<>',0)->where('shaft_orders.written_off',false)
            ->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')
            ->select('shaft_orders.*','shafts.warehouse','shafts.shaft_id')
            ->orderBy('shaft_order_number')->get();
            foreach ($shaftordersallokvid as $okvid) {
                $shaftresourse = ShaftResource::where('shaft_id',$okvid->shaft_id)->whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->sum('edition_batch');
    
                array_push($shafts_resource,[$okvid,$shaftresourse ?? 0]);
            }

        } else {
            $order = Order::where('okvid_number',$request->okvid)->first();

            if ($order) {            
                $layoutconstructor = LayoutConstructor::where('okvid_number',$request->okvid)->get();

                $shaftresourses = ShaftResource::where('okvid_number',$request->okvid)->get();

                $shaftorders = ShaftOrder::where('okvid_number',$order->okvid_number)->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')->select('shaft_orders.*','shafts.ff')->get();
                $orderstreams = OrderStream::where('okvid_number',$request->okvid)->get();

                $last_request_order = ShaftRequestComposition::where('okvid_number',$order->okvid_number)->get()->last();
                if ($last_request_order == null) {
                    $last_request_order = [0];
                }

                $ordergp = [];
                foreach ($orderstreams as $orderstream) {
        
                    $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
                    
                    if ($producttype != null) {
                        array_push($ordergp,[$producttype,$orderstream]);
                    }
        
                }

                $orders_double = Order::where('upak_order',$order->upak_order)->get();

                $shafts_resource = [];
                $shaftordersallokvid = ShaftOrder::whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->where('shaft_orders.shaft_id','<>',0)->where('shaft_orders.written_off',false)
                ->leftjoin('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id')
                ->select('shaft_orders.*','shafts.warehouse','shafts.shaft_id')
                ->orderBy('shaft_order_number')->get();
                foreach ($shaftordersallokvid as $okvid) {
                    $shaftresourse = ShaftResource::where('shaft_id',$okvid->shaft_id)->whereIn('okvid_number',$orders_double->unique('okvid_number')->pluck('okvid_number'))->sum('edition_batch');
        
                    array_push($shafts_resource,[$okvid,$shaftresourse ?? 0]);
                }
            }
        }
       
        return ([$order,$shaftorders ?? null,$ordergp,$shaftresourses,$layoutconstructor,$shafts_resource,$last_request_order]);
    }

    public function deleteGp(Request $request)
    {
        $stream = OrderStream::where('okvid_number',$request->okvid)->where('gp_code',$request->gp)->delete();
        $stream = OrderStream::where('okvid_number',$request->okvid)->get();

        $ordergp = [];
        foreach ($stream as $orderstream) {

            $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
            
            if ($producttype != null) {
                array_push($ordergp,[$producttype,$orderstream]);
            }

        }

        return ($ordergp);
    }

    public function getStreams(Request $request) 
    {

        $data = OrderStream::where('okvid_number',$request->okvid)->get();

        $ordergp = [];
        foreach ($data as $orderstream) {

            $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
            
            if ($producttype != null) {
                array_push($ordergp,[$producttype,$orderstream]);
            }

        }

        return ($ordergp);
    }

    public function addGp(Request $request)
    {

        $stream = OrderStream::where('okvid_number',$request->okvid)->count();

        $newGp = New OrderStream();
        $newGp->line_number = $stream+1;
        $newGp->okvid_number = $request->okvid;
        $newGp->gp_code = $request->gp_code;
        $newGp->quantity = $request->quantity;
        $newGp->save();

        $stream = OrderStream::where('okvid_number',$request->okvid)->get();

        $ordergp = [];
        foreach ($stream as $orderstream) {

            $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
            
            if ($producttype != null) {
                array_push($ordergp,[$producttype,$orderstream]);
            }

        }

        return ($ordergp);
    }

    public function addShaft(Request $request)
    {
        
        $shaft = ShaftOrder::where('okvid_number',$request->okvid)->count();

        $newShaft = New ShaftOrder();
        $newShaft->shaft_order_number = $shaft+1;
        $newShaft->okvid_number = $request->okvid;
        $newShaft->shaft_id = 0;
        $newShaft->save();

        $shaft = ShaftOrder::where('okvid_number',$request->okvid)->get();

        
        return ($shaft);
    }

    public function shaftResource(Request $request)
    {

            $okvidOrders = new Navision();
            $okvidOrders->connection = 'danaflexNano';
            $okvidOrders->table = 'ParamOKViD';
            
            $okvid_order = substr_replace($request->okvid, "-", -2, 0);

            $pyURL = '/home/forge/okvid.danaflex.ru/app/Helpers/OrderMetr.py';
            $user = 'nav-port';
            $pswd = 'Shambala12!';
            $py = 'python3';
            $okvid = $okvid_order;

            $webService = 'http://dnsql.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Codeunit/WS_Global';
         
            $command = "$py $pyURL -u $user -p $pswd -l $webService -c $okvid";  #linux
            $output = shell_exec($command);
            $doc = new \DOMDocument('1.0', 'utf-8');
            $doc->loadXML($output);
            $XMLresults = $doc->getElementsByTagName("return_value");

            $shaft_resource = $XMLresults->item(0)->nodeValue/$request->format/10*$request->fragments;
            
            return $shaft_resource;
            

    }

    public function checkNavParam(Request $request)

    {

        if (mb_substr($request->order,0,1)=='N') {
            $place = 'navNANO';
        } else if (mb_substr($request->order,0,1)=='A'){
            $place = 'navALABUGA';
        } else {
            $place = 'navZAO';
        }
        
        
        
        $pyURL = '/home/forge/okvid.danaflex.ru/app/Helpers/soapNav.py';
        $user = 'nav-port';
        $pswd = 'Shambala12!';
        $py = 'python3';
        $orderNav = $request->order;
        switch ($place) {
            case 'navZAO':
                $webService = 'http://10.10.40.107:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/OrderLineItemPage';
                break;
            case 'navNANO':
                $webService = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/OrderLineItemPage';
                break;
            case 'navALABUGA':
                $webService = 'http://10.5.128.144:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/OrderLineItemPage';
                break;
            default:
                return 'error place code';
                break;
        }
        $command = "$py $pyURL -u $user -p $pswd -l $webService -a orderlineitempage:ReadMultiple -f ".'"Prod_Order_No:'.$orderNav.',Item_No:#lt;#gt;#apos;СПЕЦ#apos;"';
        $output = shell_exec($command);

        
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $output);
        $xml = new \SimpleXMLElement($response);
        $json = json_encode($xml);   
        $array = json_decode($json, TRUE);

        if (array_key_exists('OrderLineItemPage',$array['SoapBody']['ReadMultiple_Result']['ReadMultiple_Result'])) {

            $array = $array['SoapBody']['ReadMultiple_Result']['ReadMultiple_Result']['OrderLineItemPage'];
            
            if (is_array(current($array))) {

                foreach ($array as $item) {

                    $order = Order::where('id',$request->order_id)->first();

                    if ( ManagerSupport::where('code_nav',$item['otv_men'])->doesntExist()) {
                        $osz_manager = New ManagerSupport();
                        $osz_manager->manager_fio = $item['otv_men_name'] ?? null;
                        $osz_manager->code_nav = $item['otv_men'] ?? null;
                        $osz_manager->save();
                    } else {
                        $osz_manager = ManagerSupport::where('code_nav',$item['otv_men'])->first();
                    }

                    $order->prod_order = $request->order;
                    $order->width_stream = $item['ShirRuch'];
                    $order->amount_stream = $item['KolvoRuch'];
                    $order->phototag_step = $item['shagpechati'];
                    $order->material_width = $item['Shirina1'];
                    $order->manager_osz = $osz_manager->manager_fio ?? null;
                    $order->save();

                    if (array_key_exists('ShortName',$item)) {


                        if ($orderNav != '') {
                        
                            if ( !ProductType::where('gp_code',str_replace('ГП', '', $item['Item_No']))->exists()) {
                                
                                $gp = New ProductType();
                                $gp->gp_code = str_replace('ГП', '', $item['Item_No']);
                                $gp->description = $item['ShortName'];
                                $gp->brand = $item['Brand'] ?? '';
                                $gp->product_category = $item['ItemCategory'] ?? '';
                                $gp->customer = $item['CustomerName'];
                                if (($item['CustomerName'] == 'ООО "МАРС"') && (array_key_exists('Description4',$item))){
                                    $gp->sap_code = $item['Description4'] ?? null;
                                } else {
                                    $gp->sap_code = $item['SAP_code'] ?? null;
                                }
                                $gp->save();

                                $streams_order = OrderStream::where('okvid_number',$order->okvid_number)->delete();
                                
                                $stream_order = New OrderStream();
                                $stream_order->gp_code = str_replace('ГП', '', $item['Item_No']);
                                $stream_order->okvid_number = $order->okvid_number;
                                $stream_order->quantity = $item['kolvor'] ?? 1;
                                $stream_order->save();
                                
                                
                            } else {
                                
                                
                                $streams_order = OrderStream::where('okvid_number',$order->okvid_number)->where('gp_code',str_replace('ГП', '', $item['Item_No']))->delete();

                                $stream_order = New OrderStream();
                                $stream_order->gp_code = str_replace('ГП', '', $item['Item_No']);
                                $stream_order->okvid_number = $order->okvid_number;
                                $stream_order->quantity = $item['kolvor'] ?? 1;
                                $stream_order->save();
                                
                            }
                        }

                    }
                    
                }

            } else {

                //if (array_key_exists('ShortName',$array)) {

                    $order = Order::where('id',$request->order_id)->first();
                        
                    if ( ManagerSupport::where('code_nav',$array['otv_men'])->doesntExist()) {
                        $osz_manager = New ManagerSupport();
                        $osz_manager->manager_fio = $array['otv_men_name'] ?? null;
                        $osz_manager->code_nav = $array['otv_men'] ?? null;
                        $osz_manager->save();
                    } else {
                        $osz_manager = ManagerSupport::where('code_nav',$array['otv_men'])->first();
                    }

                    
                    if ($orderNav != '') {
                    
                        if ( !ProductType::where('gp_code',str_replace('ГП', '', $array['Item_No']))->exists()) {
                            
                            $gp = New ProductType();
                            $gp->gp_code = str_replace('ГП', '', $array['Item_No']);
                            $gp->description = $array['ShortName'] ?? null;;
                            $gp->brand = $array['Brand'] ?? '';
                            $gp->product_category = $array['ItemCategory'] ?? '';
                            $gp->customer = $array['CustomerName'];
                            if (array_key_exists('Description4',$array)) {
                                if (($array['CustomerName'] == 'ООО "МАРС"') && ($array['Description4'] != '')){
                                    $gp->sap_code = $array['Description4'] ?? null;
                                } else {
                                    $gp->sap_code = $array['SAP_code'] ?? null;
                                }
                            } else {
                                $gp->sap_code = $array['SAP_code'] ?? null;
                            }
                            $gp->save();

                            
                            $streams_order = OrderStream::where('okvid_number',$order->okvid_number)->delete();

                            $stream_order = New OrderStream();
                            $stream_order->gp_code = str_replace('ГП', '', $array['Item_No']);
                            $stream_order->okvid_number = $order->okvid_number;
                            $stream_order->quantity = $array['kolvor'] ?? 1;
                            $stream_order->save();
                            
                            
                            
                        } else {

                            $gp = ProductType::where('gp_code',str_replace('ГП', '', $array['Item_No']))->first();
                            $gp->description = $array['ShortName'] ?? null;;
                            $gp->brand = $array['Brand'] ?? ''; 
                            $gp->product_category = $array['ItemCategory'] ?? '';
                            $gp->customer = $array['CustomerName'];
                            if (array_key_exists('Description4',$array)) {
                                if (($array['CustomerName'] == 'ООО "МАРС"') && ($array['Description4'] != '')){
                                    $gp->sap_code = $array['Description4'] ?? null;
                                } else {
                                    $gp->sap_code = $array['SAP_code'] ?? null;
                                }
                            } else {
                                $gp->sap_code = $array['SAP_code'] ?? null;
                            }
                            $gp->save();
                            
                            $streams_order = OrderStream::where('okvid_number',$order->okvid_number)->delete();

                            $stream_order = New OrderStream();
                            $stream_order->gp_code = str_replace('ГП', '', $array['Item_No']);
                            $stream_order->okvid_number = $order->okvid_number;
                            $stream_order->quantity = $array['kolvor'] ?? 1;
                            $stream_order->save();
                            
                        } 
                    
                    }

                    $order->prod_order = $request->order;
                    $order->width_stream = $array['ShirRuch'];
                    $order->amount_stream = $array['KolvoRuch'];
                    $order->phototag_step = $array['shagpechati'];
                    $order->material_width = $array['Shirina1'];
                    $order->manager_osz = $osz_manager->manager_fio;
                    $order->save();
        
                //} 
            }
            
            return $order;
        }
        
    }

    public function submitShaft(Request $request) 
    {

        $shaft = ShaftOrder::where('id',$request->shaft['id'])->first(); 
               
        
        if ($request->shaft['shaft_id'] != $shaft->shaft_id) {
            
            if ($shaft->shaft_id != 0) {
                $shaft_not_free = Shaft::where('shaft_id',$shaft->shaft_id)->first();
                $shaft_not_free->free = 1;
                $shaft_not_free->save();
            }
            

            $shaft_free = Shaft::where('shaft_id',$request->shaft['shaft_id'])->first();
            $shaft_free->free = 0;
            //$shaft_free->note = $shaft->note;
            $shaft_free->save();
        } else {

        }


        $shaft_card = Shaft::where('shaft_id',$request->shaft['shaft_id'])->first();
        $shaft_card->free = false;
        $shaft_card->save();
        
        $shaft->written_off = false;
        $shaft->writeoff_date = null;
        $shaft->dispatch = $request->shaft['dispatch'];
        $shaft->shaft_id = $request->shaft['shaft_id'];
        $shaft->color = $request->shaft['color'];
        $shaft->panton = $request->shaft['panton'];
        $shaft->base = $request->shaft['base'];
        $shaft->lineature = $request->shaft['lineature'];
        $shaft->corner = $request->shaft['corner'];
        $shaft->cutter = $request->shaft['cutter'];
        $shaft->note = $request->shaft['note'];
        $shaft->warehouse_place = $request->shaft['warehouse_place'];
        $shaft->order_status = $shaft_card->warehouse;
        $shaft->save();
          
        return($shaft);
    }


    public function shaftTransfer(Request $request)
    {
        $shafttransfer = ShaftOrder::where('okvid_number',$request->okvid_old)->where('shaft_id',$request->shaft)->first();


        $shafttransfer->written_off = 1;
        $shafttransfer->writeoff_date = date('Y-m-d');
        $shafttransfer->save();

        $shaft_resource = shaftResource::where('shaft_id',$request->shaft)->delete();

        
        $newshaftorder = ShaftOrder::where('okvid_number',str_replace('-','',$request->okvid))->where('shaft_order_number',$request->section)->first();
        $newshaftorder->shaft_id = $request->shaft;
        $newshaftorder->order_status = $shafttransfer->order_status;
        $newshaftorder->warehouse_place = $shafttransfer->warehouse_place;
        $newshaftorder->written_off = 0;
        $newshaftorder->save();

        $shaft = Shaft::where('shaft_id',$request->shaft)->first();
        $shaft->free = 0;
        $shaft->save();
        
        $shaftorder_old = ShaftOrder::where('okvid_number',$request->okvid_old)->get();

        return $shaftorder_old;
    }

    public function getShaftResource(Request $request) 
    {
        $order = Order::where('okvid_number',$request->okvid)->first();
        $data = ShaftResource::where('okvid_number','like',$order->upak_order.'%')->where('shaft_id',$request->shaft_id)->get();

        return ($data);
    }
 

    public function addShaftResource(Request $request) 
    {
        if ($request->shaft_id == null) {

            $okvids = Order::where('upak_order',$request->okvid)->get();

            $shaftorders = ShaftOrder::whereIn('okvid_number',$okvids->unique('okvid_number')->pluck('okvid_number'))->where('shaft_id','<>',0)->where('written_off',false)->get();

            
            foreach ($shaftorders as $shaftorder) {
                $data = ShaftResource::where('okvid_number',$shaftorder->okvid_number)->where('shaft_id',$shaftorder->shaft_id)->count();
                $newShaftBatch = New ShaftResource();
                $newShaftBatch->okvid_number = $shaftorder->okvid_number;
                $newShaftBatch->shaft_id = $shaftorder->shaft_id;
                $newShaftBatch->string_number = $data+1;
                $newShaftBatch->batch = $request->shaft_bath;
                $newShaftBatch->edition_batch = $request->shaft_edition_bath;
                $newShaftBatch->batch_date = $request->shaft_bath_date;
                $newShaftBatch->macro_id = $request->okvid;
                $newShaftBatch->save();

                $macro_order = MacroOrder::where('macro_id',$request->okvid)->first();

                $shaftresourse = ShaftResource::where('shaft_id',$shaftorder->shaft_id)->sum('edition_batch');

                if (ceil($shaftresourse*100/2000000) > 80) {

                    $shaftorders_resource = ShaftOrder::where('shaft_id',$shaftorder->shaft_id)->get()->last();

                    if ($shaftorder->order_status == 'Данафлекс-НАНО') {
                        $emails = [
                            'm.tinchurina@danaflex.ru',
                            'd.nurutdinov@danaflex.ru',
                            'r.galiulin@danaflex.ru',
                            'a.maksytov@danaflex.ru',
                            'e.gabitova@danaflex.ru',
                            'a.rezchikov@danaflex.ru',
                            'r.karimov@danaflex.ru',
                        ];
                    } else {
                        $emails = [
                            'r.trubnikov@danaflex.ru',
                            'd.halimova@danaflex.ru', 
                            'a.rahmeev@danaflex.ru',
                            'i.gaifetdinov@danaflex.ru',
                            'a.abakaeva@danaflex.ru',
                            'v.arsentev@danaflex.ru',
                            'm.tinchurina@danaflex.ru',
                            'd.nurutdinov@danaflex.ru',
                        ];
                    }

                    $data = array('body'=>''.$shaftorder->okvid_number.'   '.$macro_order->description.'  '.'Вал: '.$shaftorder->shaft_id.'  '.'Ресурс: '.ceil($shaftresourse*100/2000000).'%'); 
                    foreach ($emails as $email) {
                        Mail::send('ugpc/mail_request_confirm',$data,function($message) use ($email){
                            $message->to($email)->subject("Превышен ресурс вала!");
                            $message->from('d.portal@danaflex.ru','UGPC-Portal');
                        });
                    }
                }
            }
        } else {
            $okvids = Order::where('okvid_number',$request->okvid)->first();

            $data = ShaftResource::where('okvid_number',$request->okvid)->where('shaft_id',$request->shaft_id)->count();
            $newShaftBatch = New ShaftResource();
            $newShaftBatch->okvid_number = $okvids->okvid_number;
            $newShaftBatch->shaft_id = $request->shaft_id;
            $newShaftBatch->string_number = $data+1;
            $newShaftBatch->batch = $request->shaft_bath;
            $newShaftBatch->edition_batch = $request->shaft_edition_bath;
            $newShaftBatch->batch_date = $request->shaft_bath_date;
            $newShaftBatch->macro_id = substr($request->okvid,0,-2);
            $newShaftBatch->save();

            $macro_order = MacroOrder::where('macro_id',substr($okvids->okvid,0,-2))->first();

            $shaftresourse = ShaftResource::where('shaft_id',$request->shaft_id)->sum('edition_batch');

            if (ceil($shaftresourse*100/2000000) > 80) {

                $shaftorders_resource = ShaftOrder::where('shaft_id',$request->shaft_id)->get()->last();

                if ($shaftorders_resource->order_status = 'Данафлекс-НАНО') {
                    $emails = [
                        'm.tinchurina@danaflex.ru',
                        'd.nurutdinov@danaflex.ru',
                        'r.galiulin@danaflex.ru',
                        'a.maksytov@danaflex.ru',
                        'e.gabitova@danaflex.ru',
                        'a.rezchikov@danaflex.ru',
                        'r.karimov@danaflex.ru',
                    ];
                } else {
                    $emails = [
                        
                        'r.trubnikov@danaflex.ru',
                        'v.nemtirev@danaflex.ru',
                        'a.rahmeev@danaflex.ru',
                        'i.gaifetdinov@danaflex.ru',
                        'a.abakaeva@danaflex.ru',
                        'm.tinchurina@danaflex.ru',
                        'd.nurutdinov@danaflex.ru',
                    ];
                }

                $data = array('body'=>''.substr($okvids->okvid_number, 0, strlen($okvids->okvid_number) - 2) . '-' . substr($okvids->okvid_number, -2).'   '.$macro_order->description.'  '.$request->shaft_id.'  '.ceil($shaftresourse*100/2000000).' '.'%'); 
                foreach ($emails as $email) {
                    Mail::send('ugpc/mail_request_confirm',$data,function($message) use ($email){
                        $message->to($email)->subject("Превышен ресурс вала!");
                        $message->from('d.portal@danaflex.ru','UGPC-Portal');
                    });
                }
            }
        }


        //$ShaftResource = ShaftResource::whereIn('okvid_number',$okvids->unique('okvid_number')->pluck('okvid_number'))->where('shaft_id',$request->shaft_id)->get();
        
        return ('ok');
    }

    public function SubmitResource(Request $request) 
    {
        $resource = ShaftResource::find($request->resource['id']);
        $resource->update($request->resource);
           
        return($resource);
    }

    public function deleteShaftResource(Request $request)
    {

        $shaft = ShaftResource::where('id',$request->resource)->delete();

        $shaft = ShaftResource::where('okvid_number',$request->okvid)->where('shaft_id',$request->shaft_id)->get();

        return ($shaft);
    }

    public function addSection(Request $request)
    {
        
        $shaftorders = ShaftOrder::where('okvid_number',$request->okvid)->get();
        foreach ($shaftorders as $shaftorder) {
            $shaftorder->delete();
        }
       
        for ($i = 1; $i <= $request->qtysection; $i++){
            $newShaft = New ShaftOrder();
            $newShaft->shaft_order_number = $i;
            $newShaft->okvid_number = $request->okvid;
            $newShaft->shaft_id = 0;
            $newShaft->save();
        }

        $shaftorders_new = ShaftOrder::where('okvid_number',$request->okvid)->get();
        
        return $shaftorders_new;
    }

    public function searchOrder(Request $request) 
    {
        $data = Order::with('shafts')->where('prod_order',$request->order)
        ->first();
        return $data;      
    }

    public function searchGp(Request $request) 
    {
        $data = OrderStream::where('gp_code',$request->item)->get();
        

        return $data;      
    }

    public function searchShaft(Request $request) 
    {

        $shaft_orders = ShaftOrder::where('shaft_id',$request->shaft)
        ->with('order.requestShaft')
        ->select(['*', DB::raw('writeoff_date IS NULL AS writeoff_date_null')])
        ->orderBy('writeoff_date_null')
        ->orderBy('writeoff_date')
        ->get();


        return $shaft_orders;
    }

    public function copyShaftParametrs(Request $request) 
    {
        
        $shafts_order = ShaftOrder::where('okvid_number',$request->okvid)->get()->toArray();

        $shafts_order_new = ShaftOrder::where('okvid_number',$request->shaft_param)->get();

        $i = 0;
        foreach ($shafts_order_new as $shaft_order_param) {
            
            $shaft_order_param->color = $shafts_order[$i]['color'];
            $shaft_order_param->panton = $shafts_order[$i]['panton'];
            $shaft_order_param->base = $shafts_order[$i]['base'];
            $shaft_order_param->lineature = $shafts_order[$i]['lineature'];
            $shaft_order_param->corner = $shafts_order[$i]['corner'];
            $shaft_order_param->cutter = $shafts_order[$i]['cutter'];
            $shaft_order_param->note = $shafts_order[$i]['note'];
            $shaft_order_param->save();
            $i=$i+1;
        }
        

        return $shafts_order;      
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
