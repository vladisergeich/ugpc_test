<?php

namespace App\Http\Controllers;

use Spatie\ArrayToXml\ArrayToXml;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Http\SimpleXMLElement;
use App\Models\BDGP\Customer;
use App\Models\BDGP\ProductType;
use App\Models\BDGP\Order;
use App\Models\BDGP\OrderStatus;
use App\Models\BDGP\OrderState;
use App\Models\BDGP\Profile;
use App\Models\BDGP\FormatVal;
use App\Models\BDGP\Designer;
use App\Models\BDGP\ManagerSupport;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\Material;
use App\Models\BDGP\Shaft;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\OrderStream;
use App\Models\BDGP\LayoutConstructor;
use App\Models\BDGP\ShaftResource;
use App\Models\BDGP\MountingParameter;
use App\Models\Ugpc\ShaftRequest;
use App\Models\Ugpc\GlobalData;
use App\Models\Ugpc\MovementOrder;
use App\Models\Ugpc\RouteMapCard;
use App\Models\Ugpc\WorkShiftCode;
use App\Models\Ugpc\InputControl;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\EngravingOrderStage;


use Barryvdh\DomPDF;
use App;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoicePaid;
use Barryvdh\DomPDF\PDF;
use Mockery\Undefined;
use PhpParser\Node\Expr\New_;

class BdgpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $producttypes = ProductType::all();
        $macro_orders = MacroOrder::all();
     
        return view('ugpc.bdgp.order_list',[
            'producttypes' => $producttypes, 
            'orders' => $macro_orders,
        ]);
    }


    public function submitShaftBatch(Request $request) 
    {
        $shaft = ShaftResource::find($request->shaft['id']);
        $shaft->update($request->shaft);
           
        return($shaft);
    }


    public function getOkvidCard(Request $request) 
    {
        $data = Order::where('okvid_number',$request->okvid)->first();

        return $data;
    }

    public function createdouble(Request $request) 
    {
        $orderLast = Order::where('okvid_number',$request->okvid)->first();
        $orderCount = Order::where('upak_order',$request->upakorder)->get()->last();

        $shaftorders_last = ShaftOrder::where('okvid_number',$request->okvid)->get();
        $streamsorders_last = OrderStream::where('okvid_number',$request->okvid)->get();

        foreach ($shaftorders_last as $shaftorder_last) {
            $shaftorder_new = $shaftorder_last->replicate();
            $shaftorder_new->shaft_id = 0;
            $shaftorder_new->written_off = false;
            $shaftorder_new->writeoff_date = null;
            $shaftorder_new->okvid_number = $orderCount->okvid_number+1;
            $shaftorder_new->save();
        }

        foreach ($streamsorders_last as $streamsorder_last) {
            $streamsorder_new = $streamsorder_last->replicate();
            $streamsorder_new->okvid_number = $orderCount->okvid_number+1;
            $streamsorder_new->save();
        }

        $newOrder = $orderLast->replicate();
        $newOrder->okvid_number = $orderCount->okvid_number+1;
        $newOrder->upak_order = $request->upakorder;
        $newOrder->trim_release = $request->trim_release;
        $newOrder->gap_a_quantity = 0;
        $newOrder->gap_b_quantity = 0;
        $newOrder->condition = '';
        $newOrder->order_accepted_date = date('Y-m-d');
        $newOrder->tag_rsp_quantity = 8;
        $newOrder->tag_l_quantity = 8;
        $newOrder->field_c_quantity = 5;
        $newOrder->prod_order = '';
        $newOrder->order_status = null;
        $newOrder->engraving = null;
        $newOrder->supplier = null;
        $newOrder->cylinder_quantity = null;
        $newOrder->application_manufact_bases_completed = null;
        $newOrder->request_engraving_date = null;
        $newOrder->application_install_completed = null;
        $newOrder->request_install_date = null;
        $newOrder->imprinted = false;
        $newOrder->engraved = false;
        $newOrder->comment = null;
        $newOrder->save();

        $new_movement_order = new MovementOrder();
        $new_movement_order->okvid_number = $orderCount->okvid_number+1;
        $new_movement_order->save();
        
        return $newOrder;
    }

    public function downloadPdf(Request $request)
    {   
        $order = Order::find($request->order);
        $shaftorders = ShaftOrder::where('okvid_number',$order->okvid_number)->orderBy('shaft_order_number', 'asc')->get();
        $orderstreams = OrderStream::where('okvid_number',$order->okvid_number)->get();
        $order_profile = Profile::where('short_name',$order->profile)->first();
        $macro_order = MacroOrder::where('macro_id',$order->upak_order)->first();

        $ordergp = [];
        $ve_birka = false;
        foreach ($orderstreams as $orderstream) {

            $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
            
            if ($producttype && strpos($producttype->gp_code, 'ВЭ') !== false) {
                $ve_birka = true;
            }
            if ($producttype != null) {
                array_push($ordergp,[$producttype,$orderstream]);
            }

        }

        $customerGp = OrderStream::with('productType')->where('okvid_number',$order->okvid_number)->first();

        if ($order->primary != 0) {
            if ($ve_birka == true) {
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'ВЭ БИРКА')->first();
            } else if (($customerGp->productType->customer == 'ООО "Нестле Россия"') && (ShaftOrder::whereIn('color',['Cold seal','Coldseal'])->where('okvid_number',$order->okvid_number)->orWhereIn('panton',['Cold seal','Coldseal'])->where('okvid_number',$order->okvid_number)->count() > 0)){
                
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'Нестле')->first();
            } else if (strpos($macro_order->customer, 'дэлис Русь') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'Мондэлис')->first();
            } else if (strpos($order->primary, 'PET') !== false || strpos($order->primary, 'РЕТ') !== false) {
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'PET')->first();
            } else if ($order->primary == 'BOPP жемч.') {
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'BOPP жемч')->first();
            } else if (strpos($order->primary, 'BOPP') !== false || strpos($order->primary, 'ВОРР') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'BOPP')->first();
            } else if (strpos($order->primary, 'ПВХ П') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'ПВХ П')->first();
            } else if (strpos($order->primary, 'ОПС ТУ	') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'ОПС ТУ	')->first();
            } else if (strpos($order->primary, 'ОПС_ТУ П') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'ОПС_ТУ П')->first();
            } else if (strpos($order->primary, 'ПЭТФ П') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'ПЭТФ П')->first();
            } else if (strpos($order->primary, 'Фольга') !== false){
                $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'Фольга')->first();
            } 
        } else {
            $shrinkage = null;
        }

        $order_shafts = [];
        $shaft_edge = 0;
        foreach ($shaftorders as $shaftorder) {

            $shaft = Shaft::where('shaft_id',$shaftorder->shaft_id)->first();
            
            if ($shaft != null) {
                if ($shrinkage != null) {
                    $diameter_edition = (($order->phototag_step*$order->fragment_in_circulation)/3.1416*(float)$shrinkage->variable_body)+$shaft_edge;
                } else {
                    //$diameter_edition = (($order->phototag_step*$order->fragment_in_circulation)/3.1416*1.0013)+$shaft_edge;
                    $diameter_edition = 0;
                }
                array_push($order_shafts,[$shaft,$shaftorder,$diameter_edition]);
            }

            $shaft_edge = $shaft_edge+0.03;

        }
       
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('ugpc.pdf.invoice', compact('order','shaftorders','ordergp','order_profile','order_shafts'))->setPaper('a4', 'landscape');
        

       $designer = Designer::where('fio',$order->application_install_completed)->first(); 
       $repro = Vendor::where('vendor_name',$order->repro)->first();
       $engraving = Vendor::where('vendor_name',$order->engraving)->first();
       $primary = Material::where('description',$order->primary)->first();
       $secondary = Material::where('description',$order->secondary)->first();
       $status = OrderStatus::where('order_status',$order->order_status)->first();

       $macroorder = MacroOrder::where('macro_id',$order->upak_order)->first();

       $xml_main = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
       <dataroot xmlns:od="urn:schemas-microsoft-com:officedata" generated="2022-02-07T16:50:16">
       </dataroot>
       ');  
      
        foreach ($orderstreams as $orderstream) {

            $producttype = ProductType::where('gp_code',$orderstream->gp_code)->first();
            
            $xml_main_body = $xml_main->addChild('Запрос_экспортXML');  

            $xml_main_body->addChild('ОбщееОписание',htmlspecialchars($macroorder->description));
            $xml_main_body->addChild('Номер_x0020_ОКВиД',$order->okvid_number);
            $xml_main_body->addChild('Списан',$macroorder->written_off);
            $xml_main_body->addChild('серия_x0020_краски',$order->paint_series);
            $xml_main_body->addChild('Заявка_x0020_на_x0020_монтаж',$order->request_install_date);
            $xml_main_body->addChild('Число_x0020_стадий',$order->number_stages);
            $xml_main_body->addChild('фрагментов_x0020_в_x0020_обороте',$order->fragment_in_circulation);
            $xml_main_body->addChild('Поле_x0020_С',5);
            $xml_main_body->addChild('зазор_x0020_B',0);
            $xml_main_body->addChild('зазор_x0020_А',0);
            $xml_main_body->addChild('метки_x0020_L',8);
            $xml_main_body->addChild('Метки_x0020_RSP',8);
            $xml_main_body->addChild('цвет_x0020_линии_x0020_реза',$order->cut_line_color);
            $xml_main_body->addChild('Варианты_x0020_намотки',$order->winding_option);
            $xml_main_body->addChild('шаг_x0020_фотометки',$order->phototag_step);
            $xml_main_body->addChild('кол-во_x0020_ручьев',$order->amount_stream);
            $xml_main_body->addChild('метки_x0020_приводки_x0020_слева',$order->drive_label_left);
            $xml_main_body->addChild('_x0022_светофор_x0022__x0020_слева',$order->traffic_lights_left);
            $xml_main_body->addChild('Ширина_x0020_ручья',$order->width_stream);
            $xml_main_body->addChild('Метка_x0020_CS',$order->marker_cs);
            $xml_main_body->addChild('линия_x0020_для_x0020_резки_x0020_слева',$order->cutting_line_left);
            $xml_main_body->addChild('линия_x0020_для_x0020_резки_x0020_справа',$order->cutting_line_right);
            $xml_main_body->addChild('кресты_x0020_слева',$order->cross_left);
            $xml_main_body->addChild('кресты_x0020_справа',$order->cross_right);
            $xml_main_body->addChild('_x0022_светофор_x0022__x0020_справа',$order->traffic_lights_right);
            $xml_main_body->addChild('вторичка',$secondary->id ?? 0);
            $xml_main_body->addChild('метки_x0020_приводки_x0020_справа',$order->drive_label_right);
            $xml_main_body->addChild('первичка',$primary->id ?? 0);
            $xml_main_body->addChild('Expr1029','0');
            $xml_main_body->addChild('Электронный_x0020_файл',$order->electronic_file);
            $xml_main_body->addChild('Промобразец',$order->promo_sample);
            $xml_main_body->addChild('Состояние',$order->condition);
            $xml_main_body->addChild('Папка',$order->prod_order);
            $xml_main_body->addChild('Expr1034','1');
            $xml_main_body->addChild('Код_x0020_ГП',$orderstream->gp_code ?? '');
            $xml_main_body->addChild('Формат',$order->format);
            $xml_main_body->addChild('Краткое_x0020_название',$order->profile);
            $xml_main_body->addChild('Expr1038','7');
            $xml_main_body->addChild('Увеличение_x0020_по_x0020_градациям',$order->gradation_increase);
            $xml_main_body->addChild('Количество_x0020_цилиндров',$order->cylinder_quantity);
            $xml_main_body->addChild('Печать',$order_profile->print);
            $xml_main_body->addChild('Допечатная_x0020_подготовка',$order->prepress);
            $xml_main_body->addChild('Expr1043','0');
            $xml_main_body->addChild('Пробопечать',$order->test_print);
            $xml_main_body->addChild('Цветопроба',$order->color_proof);
            $xml_main_body->addChild('Перегравировка_x0020_заказа',$order->order_reengraving);
            $xml_main_body->addChild('Гравировка_x0020_с_x0020_изменением',$order->engraving_with_change);
            $xml_main_body->addChild('Изменение_x0020_диаметра',$order->diametr_change);
            $xml_main_body->addChild('Перехромирование',$order->overchroming);
            $xml_main_body->addChild('Новая_x0020_работа',$order->new_job);
            $xml_main_body->addChild('ВнешКоментарий1',$order->external_comment);
            $xml_main_body->addChild('Ширина_x0020_запеч_x0020_мат-ла',$order->material_width);
            $xml_main_body->addChild('ICC-аналог_x0020_поставщика',$order_profile->supplier_analog_icc);
            $xml_main_body->addChild('Код',$repro->id);
            $xml_main_body->addChild('МенеджерОЗС',$order->manager_osz);
            $xml_main_body->addChild('название',htmlspecialchars($producttype->description) ?? '');
            $xml_main_body->addChild('сапкод',$producttype->sap_code ?? '');
            $xml_main_body->addChild('Статус_x0020_заказа',$status->id);
            $xml_main_body->addChild('Изготовление_x0020_болванки','0');
            $xml_main_body->addChild('Заявку_x0020_на_x0020_монтаж_x0020__x0020_заполнил',$order->application_install_completed);
            $xml_main_body->addChild('Заказчик',$producttype->customer ?? '');
            $xml_main_body->addChild('Категория',$producttype->product_category ?? '');
            $xml_main_body->addChild('Бренд',$producttype->brand ?? '');
            $xml_main_body->addChild('Папки_x0020_нет',$order->no_folder);
            $xml_main_body->addChild('Репро',$repro->id);
            $xml_main_body->addChild('Гравировка',$engraving->id);
            $xml_main_body->addChild('Тест_x0020__x2116_',$order->test_number);
            $xml_main_body->addChild('Длина_гильзы',$order->sleeve_lenght);


        }


        $xml_shaft = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
            <dataroot xmlns:od="urn:schemas-microsoft-com:officedata" generated="2022-02-07T16:50:16">
            </dataroot>
            ');  


            $shaft_edge = 0;
        foreach ($shaftorders as $shaftorder) {

            $shaft = Shaft::where('shaft_id',$shaftorder->shaft_id)->first();
            
            if ($shaft != null) {
                if ($shrinkage != null) {
                    $diameter_edition = (($order->phototag_step*$order->fragment_in_circulation)/3.1416*(float)$shrinkage->variable_body)+$shaft_edge;
                } else {
                    $diameter_edition = 0;
                }
                array_push($order_shafts,[$shaft,$shaftorder,$diameter_edition]);
            }

            $shaft_edge = $shaft_edge+0.03;

        }
            
            
        $shaft_edge = 0;
        foreach ($shaftorders as $shaftorder) {

            $shaft = Shaft::where('shaft_id',$shaftorder->shaft_id)->first();

            $shaftorders_first = ShaftOrder::where('okvid_number',$order->okvid_number)->orderBy('shaft_order_number', 'asc')->first();

            $xml_shaft_body = $xml_shaft->addChild('Запрос_экспортXML_валы');  
        
            $xml_shaft_body->addChild('Номер_x0020_ОКВиД', $shaftorder->okvid_number);   
            $xml_shaft_body->addChild('НомерВалаЗаказа', $shaftorder->shaft_order_number);    
            $xml_shaft_body->addChild('ID_x0020_вала', $shaftorder->shaft_id);    
            $xml_shaft_body->addChild('цвет', $shaftorder->color);    
            $xml_shaft_body->addChild('Pantone', $shaftorder->panton); 
            $xml_shaft_body->addChild('линиатура', $shaftorder->lineature);       
            $xml_shaft_body->addChild('угол', $shaftorder->corner);     
            $xml_shaft_body->addChild('резец', $shaftorder->cutter);      
            $xml_shaft_body->addChild('свободен', $shaft->free); 
            $xml_shaft_body->addChild('Примечание', $shaftorder->note); 

            if ($shrinkage != null) {
                $diameter_edition = (($order->phototag_step*$order->fragment_in_circulation)/3.1416*(float)$shrinkage->variable_body)+$shaft_edge;
            } else {
                $diameter_edition = 0;
            }

            $xml_shaft_body->addChild('Диаметр_цилиндра', round($diameter_edition,3));

            
            if ($shaftorders_first->ff == 'SC') {
                $xml_shaft_body->addChild('shirina_gilzy',1370); 
            } else if ($shaftorders_first->ff == 'HS') {
                $xml_shaft_body->addChild('shirina_gilzy',1380); 
            } else {
                $xml_shaft_body->addChild('shirina_gilzy',1400); 
            }  
            $xml_shaft_body->addChild('Заявку_x0020_на_x0020_монтаж_x0020__x0020_заполнил', $order->application_install_completed);  

            $shaft_edge = $shaft_edge+0.03;
        }

        if ($order->print) {
            $clarified = -1;
        }else {
            $clarified = 0;
        }

        if ($order->engraving != 'Данафлекс-НАНО') {
            $order->condition = 'Ожидание монтажа от поставщика';
            $order->save();
        }
                           

        $this->createRouteMap($order->okvid_number);
        if ($order->order_status == 'Изменение диаметра баз') {
            $pdf_name = $order->field_c_quantity.'_'.$order->field_c_quantity.'_'.'1'.'_'.
                        $order->okvid_number.'_'.
                        $order->order_accepted_date.'_'.'0'.'_'.$designer->reduction.'_'.
                        $order->prod_order.'_'.$order->fragment_in_circulation.'_'.$clarified.'_'.
                        $repro->id.'_'.
                        $engraving->id.'.pdf';
        } else {
            $pdf_name = $order->field_c_quantity.'_'.$order->field_c_quantity.'_'.'2'.'_'.
                        $order->okvid_number.'_'.
                        $order->order_accepted_date.'_'.'0'.'_'.$designer->reduction.'_'.
                        $order->prod_order.'_'.$order->fragment_in_circulation.'_'.$clarified.'_'.
                        $repro->id.'_'.
                        $engraving->id.'.pdf';
        } 
        
        $response = Http::attach('attachment',$xml_main->saveXML() , '1.xml') 
        ->attach('attachment',$xml_shaft->saveXML() , '2.xml') 
        ->attach('attachment', $pdf->output() ,$pdf_name)
        ->post('http://10.10.40.94:4415/ws/zayavka', $request->all());

        return response($pdf->output(),)
              ->header('Content-Type', 'application/pdf'); 
    }

    public function getShaftOrderNumber($shaftId,$okvid)
    { 
        $ShaftOrder = ShaftOrder::where('shaft_id',$shaftId)->where('okvid_number',$okvid)->first();    

        $shaftNumber = ShaftOrder::where('okvid_number',$okvid)->where('shaft_id','<>',0)->where('id','<=',$ShaftOrder->id)->count();    

        return $shaftNumber;
    }

    public function getShaftSeparationNumber($shaftId,$okvid)
    { 

        $shaftOrders = ShaftOrder::where('okvid_number',$okvid)->get();    

        $separation = 0;
    
        foreach ($shaftOrders as $shaftOrder) {
            $separation++;

            if ($shaftOrder->color == 'empty') {
                $separation--;
            }

            $lineatureSlashes = substr_count($shaftOrder->lineature, '/');
            $cornerSlashes = substr_count($shaftOrder->corner, '/');
            $cutterSlashes = substr_count($shaftOrder->cutter, '/');
    
            $maxSlashes = max($cornerSlashes, $cutterSlashes, $lineatureSlashes);
    
            $separation += $maxSlashes;
    
            if ($shaftOrder->shaft_id == $shaftId) {
                return $separation;
            }
        }
    }

    public function getShrinkage($order)
    { 
        $orderStreams = OrderStream::where('okvid_number',$order->okvid_number)->get();
        $macroOrder = MacroOrder::where('macro_id',$order->upak_order)->with('customerId')->first();
            
        $ve_birka = false;
        foreach ($orderStreams as $orderStream) {

            if (strpos($orderStream->gp_code, 'ВЭ') !== false) {
                $ve_birka = true;
            }

        }

        $customerGp = OrderStream::with('productType')->where('okvid_number',$order->okvid_number)->first();

        if ($ve_birka == true) {
            $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'ВЭ БИРКА')->first();
            return $shrinkage->variable_body;
        } else if (($customerGp->productType->customer == 'ООО "Нестле Россия"') && (ShaftOrder::whereIn('color',['Cold seal','Coldseal'])->where('okvid_number',$order->okvid_number)->orWhereIn('panton',['Cold seal','Coldseal'])->where('okvid_number',$order->okvid_number)->count() > 0)){
            $shrinkage = GlobalData::where('variable_name','shrinkage')->where('variable_desc', 'Нестле')->first();
            return $shrinkage->variable_body;

        } else if ($macroOrder->customerId->id == 23 || $macroOrder->customerId->id == 178) {
            $shrinkage = GlobalData::where('variable_name', 'shrinkage')
            ->where('variable_desc', 'Мондэлис')
            ->first();
            return $shrinkage->variable_body;
        } else if ((strpos($order->primary, 'РЕТ') !== false) || (strpos($order->primary, 'PET') !== false)) {
            $shrinkage = GlobalData::where('variable_name', 'shrinkage')
                ->where('variable_desc', 'PET')
                ->first();

                return $shrinkage->variable_body;
        } else if ($order->primary == 'BOPP жемч.') {
            $shrinkage = GlobalData::where('variable_name', 'shrinkage')
                ->where('variable_desc', 'BOPP жемч')
                ->first();
                return $shrinkage->variable_body;
        } else if ((strpos($order->primary, 'BOPP') !== false) || (strpos($order->primary, 'ВОРР') !== false)) {
            $shrinkage = GlobalData::where('variable_name', 'shrinkage')
                ->where('variable_desc', 'BOPP')
                ->first();
                return $shrinkage->variable_body;
        } else if ((strpos($order->primary, 'ОПС_ТУ') !== false)) {
            $shrinkage = GlobalData::where('variable_name', 'shrinkage')
                ->where('variable_desc', 'ОПС_ТУ П')
                ->first();
                return $shrinkage->variable_body;
        } else {
            return 0;
        }
    }

    public function getFinalDiameter($order,$shaftNumber)
    { 
        $shrinkage = $this->getShrinkage($order);
        $shaftEdge = ($shaftNumber - 1) * 0.03;

        if ($shrinkage != 0) {
            $finalDiameter = (($order->phototag_step*$order->fragment_in_circulation)/3.1416*(float)$shrinkage)+$shaftEdge;
        } else {
            $finalDiameter = (($order->phototag_step*$order->fragment_in_circulation)/3.1416*1.0013)+$shaftEdge;
        }

        return $finalDiameter;
    }

    public function createRouteMap($okvid)
    { 
        $work_shift_code = WorkShiftCode::where('open',true)->first();
        $shaftsorder = ShaftOrder::where('okvid_number',$okvid)->where('shaft_id','<>',0)->get();    
        $order = Order::where('okvid_number',$okvid)->first();

        foreach ($shaftsorder as $shaftorder){

            $shaft = Shaft::where('shaft_id',$shaftorder->shaft_id)->first();  
            

            $engravingOrder = EngravingOrder::where('design_order_id',$order->id)->where('shaft_id',$shaft->id)->first();

            $engravingOrderUpdate = EngravingOrder::updateOrCreate(
                ['design_order_id' => $order->id, 'shaft_id' => $shaft->id],
                [   'separation_number' => $this->getShaftSeparationNumber($shaftorder->shaft_id,$shaftorder->okvid_number),
                    'shaft_number' => $this->getShaftOrderNumber($shaftorder->shaft_id,$shaftorder->okvid_number),
                    'final_size' => !$engravingOrder || $engravingOrder->final_size == null ? round($this->getFinalDiameter($order,$shaftorder->shaft_order_number),3) : $engravingOrder->final_size,
                    'status' => !$engravingOrder || $engravingOrder->status == null ? 'Ожидает подтверждения' : $engravingOrder->status,
                    'cutter' => $shaftorder->cutter,
                    'corner' => $shaftorder->corner,
                    'color' => $shaftorder->color ?? $shaftorder->panton,
                    'lineature' => $shaftorder->lineature,
                    'format' => $order->phototag_step*$order->fragment_in_circulation
                ],
            );

            $engravingOrderStage = EngravingOrderStage::updateOrCreate(
                ['engraving_order_id' => $engravingOrderUpdate->id, 'stage_number' => 1],
                [  
                    'stage_number' => 1
                ],
            );

        }


        return 'ok';
    }



    public function createStreamXml(Request $request) 
    {
        $order = Order::find($request->order);

        $xml_stream = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <dataroot xmlns:od="urn:schemas-microsoft-com:officedata" generated="2022-02-07T16:50:16">
        </dataroot>
        ');  

        $macroorder = MacroOrder::where('macro_id',$order->upak_order)->first();
        $designer = Designer::where('fio',$order->designer_new)->first();
        $MountingParameter = MountingParameter::where('description',$macroorder->crosses_bleed)->first();
        $layout_constructors = LayoutConstructor::where('okvid_number',$order->okvid_number)->get();
    
        foreach ($layout_constructors as $layout_constructor) {

            $xml_stream_body = $xml_stream->addChild('EskoVerst');  

            
            $xml_stream_body->addChild('Номер_x0020_ОКВиД', $layout_constructor->okvid_number);   
            $xml_stream_body->addChild('_x2116_ручья', $layout_constructor->stream_number);    
            $xml_stream_body->addChild('Код_x0020_ГП', $layout_constructor->gp_code);    
            $xml_stream_body->addChild('Папка', $order->prod_order);    
            $xml_stream_body->addChild('Заявка_x0020_на_x0020_монтаж', $order->request_install_date); 
            $xml_stream_body->addChild('СтатусЗаказа', $order->order_status);       
            $xml_stream_body->addChild('Состояние', $order->condition);     
            $xml_stream_body->addChild('МенеджерОЗС', $order->manager_osz);      
            $xml_stream_body->addChild('код_x0020_Nav', $designer->id);    
            $xml_stream_body->addChild('Дизайнер', $order->designer_new);  
            $xml_stream_body->addChild('Формат', $order->format);  
            $xml_stream_body->addChild('Код', '1');  
            $xml_stream_body->addChild('ОбщееОписание', $macroorder->description);  
            $xml_stream_body->addChild('Краткое_x0020_название', $order->profile);  
            $xml_stream_body->addChild('Ширина_x0020_запеч_x0020_мат', $order->material_width); 
            $xml_stream_body->addChild('КрестыБлиды', $MountingParameter->id); 
            $xml_stream_body->addChild('ESKO', '0'); 
            $xml_stream_body->addChild('Заявку_x0020_на_x0020_монтаж_x0020__x0020_заполнил', $order->application_install_completed); 
            $xml_stream_body->addChild('RotateFactor', $macroorder->rotate_factor); 
            $xml_stream_body->addChild('BezReza', $macroorder->bez_reza);  
            $xml_stream_body->addChild('AutoSmesheniye', $macroorder->auto_offset);  
            $xml_stream_body->addChild('ColdSeal', $macroorder->cold_seal);  
            $xml_stream_body->addChild('relsa', $macroorder->relsa);
            if ($order->number_stages == 'НЕЧЕТНОЕ') {
                $xml_stream_body->addChild('shetnechet', 0); 
            } else {
                $xml_stream_body->addChild('shetnechet', 1); 
            }
            $xml_stream_body->addChild('кол-во_x0020_ручьев', $order->amount_stream);  
            $xml_stream_body->addChild('zazor', $macroorder->zazor);   
        } 
        
        $response = Http::attach('attachment',$xml_stream->saveXML() , '+ROTOVerst.xml') ->post('http://10.10.40.94:4415/ws/EskoVerstTest', $request->all());

        return $xml_stream;
    }



    public function createShaftsXml(Request $request) 
    {
        $order = Order::find($request->order);
        $shaftorders = ShaftOrder::where('okvid_number',$order->okvid_number)->orderBy('shaft_order_number')->get();

        $xml_shafts = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <dataroot xmlns:od="urn:schemas-microsoft-com:officedata" generated="2022-02-07T16:50:16">
        </dataroot>
        ');  

        foreach ($shaftorders as $shaftorder) {

            $xml_shafts_body = $xml_shafts->addChild('EskoParam');  
            $xml_shafts_body->addChild('Номер_x0020_ОКВиД', $order->okvid_number);   
            $xml_shafts_body->addChild('НомерВалаЗаказа', $shaftorder->shaft_order_number);    
            $xml_shafts_body->addChild('ID_x0020_вала', $shaftorder->shaft_id);    
            $xml_shafts_body->addChild('Pantone', $shaftorder->panton);   
            $xml_shafts_body->addChild('Базовый', $shaftorder->base);  
            $xml_shafts_body->addChild('линиатура', $shaftorder->lineature); 
            $xml_shafts_body->addChild('угол', $shaftorder->corner); 
            $xml_shafts_body->addChild('резец', $shaftorder->cutter); 
        }
    
        $response = Http::attach('attachment',$xml_shafts->saveXML() , '-ROTOParam.xml') ->post('http://10.10.40.94:4415/ws/zayavka', $request->all());

        return $xml_shafts;
    }

    public function createToNavXml($order_number,$okvid_number) 
    {
        $order = Order::where('prod_order',$order_number)->where('okvid_number',$okvid_number)->first();
        $order->request_engraving_date = date('Y-m-d');
        $order->save();

        $macroorder = MacroOrder::where('macro_id',$order->upak_order)->first();
        $order_profile = Profile::where('short_name',$order->profile)->first();
        $vendor = Vendor::where('vendor_name',$order->engraving)->first();
        $osz = ManagerSupport::where('manager_fio',$order->manager_osz)->first();

        $xml_okvid_nav = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <dataroot xmlns:od="urn:schemas-microsoft-com:officedata" generated="2022-02-07T16:50:16">
        </dataroot>
        ');  
        

        $xml_okvid_nav_body = $xml_okvid_nav->addChild('Esko-UTV');  
        $xml_okvid_nav_body->addChild('ОбщееОписание', $macroorder->description);   
        $xml_okvid_nav_body->addChild('Заявку_x0020_на_x0020_гравировку_x0020__x0020_заполнил', $order->engraving_request_filled);    
        $xml_okvid_nav_body->addChild('Папка', $order->prod_order);    
        $xml_okvid_nav_body->addChild('Номер_x0020_ОКВиД', $order->okvid_number);   
        $xml_okvid_nav_body->addChild('Заявка_x0020_на_x0020_гравировку', $order->request_engraving_date);  
        $xml_okvid_nav_body->addChild('ПлюсНеделя', date('d.m.Y', strtotime($order->request_engraving_date." +7 day")));  
        $xml_okvid_nav_body->addChild('Код', $vendor->id); 
        $xml_okvid_nav_body->addChild('МенеджерОЗС', $osz->id); 
    
        $response = Http::attach('attachment',$xml_okvid_nav->saveXML() , 'Esko-UTV'.'.xml') ->post('http://aekazan.danaflex.local:4415/ws/zayavka');

        return $xml_okvid_nav;
    }

    public function okvidPrinted(Request $request)
    {
        $okvid = Order::where('prod_order',$request->order)->where('okvid_number',$request->okvid)->first();
        
        $okvid->imprinted = true;
        $okvid->condition = $request->Status;
        $okvid->save(); 
        
        $this->createToNavXml($request->order,$request->okvid);
        
        return 'ok';
    }

    public function okvidCondition(Request $request)
    {
        
        $okvid = Order::where('prod_order',$request->order)->get()->last();
        $okvid->condition = $request->Status;
        $okvid->save();      
        
        return 'ok';
    }

    public function importOkvidComplect(Request $request)
    {   
        $okvid = Order::where('okvid_number',$request[0])->first();        
        return $okvid;
    }

    public function importOkvidPlate(Request $request)
    {
        $okvid = ShaftOrder::where('okvid_number',$request[0])->get(); 
        return ['plates' => $okvid];
    }

    public function importOkvidStream(Request $request)
    {   
        $streams = OrderStream::where('okvid_number',$request[0])->get();      
        return ['plates' => $streams];
    }

    public function importGpInfo(Request $request)
    { 
        $gp = ProductType::where('gp_code',$request[0])->get();      
        return $gp;
    }

    public function importMacroInfo(Request $request)
    { 
        $macro = MacroOrder::where('macro_id',$request[0])->get();       
        return $macro;
    }

    public function importMacroInfo2(Request $request)
    {
        $macro = MacroOrder::where('macro_id',$request->id)->get();     
        return $macro;
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
    public function update(Request $request)
    {
        $order = Order::find($request->order['id']);
        $order->update($request->order);

        if ($request->order['profile'] != ''){

            $order_profile = Profile::where('short_name',$request->order['profile'])->first();

            $order->supplier_engraving = $order_profile->engraver;
            $order->primary = $order_profile->primary;
            $order->secondary = $order_profile->secondary_housing;
            $order->paint_series = $order_profile->paint_series;
            $order->output = $order_profile->print;
            $order->save();
        }

        $order = Order::find($request->order['id']);
           
        return($order);
    }

    public function updateShafts(Request $request)
    {
        $shaft = ShaftOrder::find($request->shaft['id']);
        $shaft->update($request->shaft);
           
        return($request);
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
