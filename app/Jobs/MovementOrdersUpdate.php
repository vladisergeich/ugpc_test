<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Services\SOAP\SoapClient;
use App\Models\BDGP\Order;
use App\Models\Ugpc\GlobalData;
use App\Models\Ugpc\MovementOrder;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\ShaftOrder;
use App\Models\Ugpc\ShaftRequestComposition;
use Carbon\Carbon;


class MovementOrdersUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 800;
    public $order;
    /**

     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->order = $data['order'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $currentDate = Carbon::now();
            $place = null;
        
            $orders = MovementOrder::where('okvid_number',$this->order->okvid_number)->get();

        foreach ($orders as $order) {

            
            $okvid = Order::where('okvid_number',$order->okvid_number)->first();

            if ($okvid != null) {
                $shaft_request = ShaftRequestComposition::where('okvid_number',$okvid->okvid_number)->where('destination','Гравировка')->orderBy('id','desc')->first(); 
                $shaft_request_print = ShaftRequestComposition::where('okvid_number',$okvid->okvid_number)->whereIn('destination',['Печать','Ламинация'])->orderBy('id','desc')->first(); 
            

            
            $macro_order = MacroOrder::where('macro_id',$okvid->upak_order)->first();

            if ($okvid->prod_order != null) {
                
                if ((mb_substr($okvid->prod_order,0,1) == 'A') || (mb_substr($okvid->prod_order,0,1) == 'a')){
                    $place = 'navALABUGA';
                } else if ((mb_substr($okvid->prod_order,0,1) == 'N') || (mb_substr($okvid->prod_order,0,1) == 'n')) {
                    $place = 'navNANO';
                } else {
                    $place = 'navZAO';
                }
        
                switch ($place) {
                    case 'navZAO':
                        $url = 'http://10.10.40.107:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/ProdOrderCard';
                        
                        break;
                    case 'navNANO':
                        $url = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/ProdOrderCard';
                        
                        break;
                    case 'navALABUGA':
                        $url = 'http://10.5.128.144:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/ProdOrderCard';
                        
                        break;
                    default:
                        return 'error place code';
                        break;
                }

                $options = [
                    'login' => 'nav-port-ugpc',
                    'password' => 'Kdf9LOkseb38&SADgb3n'
                ];
        
                $client = new SoapClient($url,$options);
                $result = $client->ReadMultiple(
                    array( 
                        'filter'=>[ 
                            [
                            'Field' => 'No',
                            'Criteria' => "$okvid->prod_order",
                            ],
                        ],
                        'setSize' => 1
                    ) 
                )->{'ReadMultiple_Result'};
                 
                if (!empty($result->{'ProdOrderCard'})) {

                    $result = $result->{'ProdOrderCard'};

                    $order->printing_date = $result->Printing_Date_Last ?? null;
                    $order->customer_shipment_date = $result->Date_complete ?? null;
                    $order->novizna = str_replace('_', ' ', $result->novizna) ?? ' ';
                    $order->edit_design = $result->EditDesign;
                    $order->stage_order = $result->StageOrder;
                    if (!empty($result->No_Previous)){

                        $previos_order = Order::where('prod_order',$result->No_Previous)->first();
                        if ($previos_order != null) {
                            $order->previous_engraver = $previos_order->engraving;
                        }
                        $order->previous_order_number = $result->No_Previous ?? null;
                        $order->previous_order_printing_date = $result->Printing_Previous_Date ?? null;
                        }
                        

                }
            }



            $order->okvid_number = $okvid->okvid_number;
            $order->order_number = $okvid->prod_order;
            if (!$order->removed) {
                if ($okvid->request_install_date == null && $okvid->request_engraving_date == null) {
                    $order->status = 'Заказы, у которых нет заявок/нет файлов';
                } else if ($okvid->request_install_date != null && $okvid->request_engraving_date == null) {
                    $order->status = 'Заказы с наличием заявки на гравировку / job ticket / валов';
                } else if ($okvid->request_install_date != null && $okvid->request_engraving_date != null) {
                    $order->status = 'Заказы заполнены и готовы в работу';
                }
            }
            $order->customer = $macro_order->customer ?? '';
            if ($macro_order != null) {
                $order->description = $macro_order->description;
            }
            if ($shaft_request != null) {
                $order->transfer_document = $shaft_request->document_number;
            } else {
                $order->transfer_document = '';
            }

            if ($shaft_request_print != null) {
                $order->transfer_document_print = $shaft_request_print->document_number;
            } else {
                $order->transfer_document_print = '';
            }

            if ($okvid->condition != null) {
                $order->technical_condition = $okvid->condition;
            } else {
                $order->technical_condition = '';
            }
            $order->profil_engraving = $okvid->supplier_engraving;
            $order->repro = $okvid->supplier_engraving;

            $next_okvid = Order::where('okvid_number','<>',$okvid->okvid_number)->where('prod_order',$okvid->prod_order)->latest()->first(); 
            if (($next_okvid != null) && ($next_okvid->id > $okvid->id)) {
                $order->next_order_number = $next_okvid->prod_order;
            } else if (($next_okvid != null) && ($next_okvid->id < $okvid->id)) {
                $order->previos_okvid = $next_okvid->prod_order;
            }

            $shafts_numbers = ShaftOrder::where('okvid_number',$okvid->okvid_number)->where('shaft_id','<>',0)->get();

            $movement_shafts_separation = MovementOrder::where('okvid_number',$this->order->okvid_number)->orderBy('id')->get();

            if ($movement_shafts_separation->count() > 1) {
                /*
                if ($movement_shafts_separation->sum('shaft_quantity') != $okvid->cylinder_quantity) {
                    $firstItem = $movement_shafts_separation->first();                   
                    $firstItemId = $firstItem->id;

                    MovementOrder::where('okvid_number', $this->order->okvid_number)->where('id', '<>', $firstItemId)->delete();

                    $movement_shafts_separation = MovementOrder::where('okvid_number',$this->order->okvid_number)->orderBy('id')->first();
                    $movement_shafts_separation->shaft_quantity = $okvid->cylinder_quantity;
                    $movement_shafts_separation->save();
                }
                */
            } else {
                $order->shaft_quantity = $okvid->cylinder_quantity;
            }

            $shafts = '';
            foreach ($shafts_numbers as $shaft) {
                $shafts .= $shaft->shaft_id . ', ';
            }
            $shafts = rtrim($shafts, ', '); 
            $order->shafts_numbers = $shafts;

            if ($order->novizna == null) { 
                $order->novizna = 'Повторный без изменений';
            }
            $order->save();

            
            if ($okvid->prod_order != null && $place != null) {
                    switch ($place) {
                        case 'navZAO':
                            $url = 'http://10.10.40.107:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/MovementOrdersPage';
                            break;
                        case 'navNANO':
                            $url = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/MovementOrdersPage';
                            break;
                        case 'navALABUGA':
                            $url = 'http://10.5.128.144:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/MovementOrdersPage';
                            break;
                        default:
                            return 'error place code';
                            break;
                    }
                    
                    $options = [
                        'login' => 'nav-port-ugpc',
                        'password' => 'Kdf9LOkseb38&SADgb3n'
                    ];
            
                    $client = new SoapClient($url,$options);
                    $result = $client->ReadMultiple(
                        array( 
                            'filter'=>[ 
                                [
                                'Field' => 'PO',
                                'Criteria' => "'{$this->order->order_number}'",
                                ],
                                [
                                'Field' => 'OperationType',
                                'Criteria' => "Глубокая печать|Флексопечать|Ламинация",
                                ],
                                [
                                'Field' => 'Order_Snyat',
                                'Criteria' => "false",
                                ],
                                [
                                'Field' => 'MachineCenter',
                                'Criteria' => "H-1|H-2|L-SC",
                                ],
                            ],
                            'setSize' => 5
                        ) 
                    )->{'ReadMultiple_Result'};

                    if (!empty($result->{'MovementOrdersPage'})) {
                        $movementPages = $result->{'MovementOrdersPage'};
                        
                        // Если пришел один элемент, а не массив
                        if (!is_array($movementPages)) {
                            $movementPages = [$movementPages];
                        }
                    
                        foreach ($movementPages as $item) {
                            if (is_object($item)) {
                                $item->Engraver = $this->order->engraver ?? '';
                                $item->Send_Shafts = $this->order->transfer_document ?? '';
                                $item->Receiving_Shafts = $this->order->transfer_document_print ?? '';
                                if ($this->order->completion_date > $item->Completion_date_shafts) {
                                    $item->Completion_date_shafts = $this->order->completion_date ?? '';
                                }
                            }
                        }
                    
                        $object_nav = new \stdClass;
                        $object_nav->MovementOrdersPage_List = $result;
                        $client->UpdateMultiple($object_nav);
                    }
                    
            }


        }
    }
        
    }
}
