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
use App\Models\Ugpc\ShaftRequestComposition;


class MovementOrdersNewData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $last_movement_okvid = GlobalData::where('variable_name','last_movement_okvid')->first();
        $last_order = Order::where('okvid_number',$last_movement_okvid->variable_body)->first();

        $new_orders = Order::where('id','>',$last_order->id)->orderBy('id','asc')->get();
        

        foreach ($new_orders as $new_order) {

            if ( MovementOrder::where('okvid_number',$new_order->okvid_number)->doesntExist()) {
                
                $shaft_request = ShaftRequestComposition::where('okvid_number',$new_order->okvid_number)->get()->last();


                $macro_order = MacroOrder::where('macro_id',$new_order->upak_order)->first();

                if ($macro_order != null) {

                    if ( MovementOrder::where('okvid_number',$new_order->okvid_number)->doesntExist()) {
                    $new_movement_order = new MovementOrder();
                    if ($new_order->prod_order != null) {

                        
                        if ((mb_substr($new_order->prod_order,0,1) == 'A') || (mb_substr($new_order->prod_order,0,1) == 'a')){
                            $place = 'navALABUGA';
                        } else if ((mb_substr($new_order->prod_order,0,1) == 'N') || (mb_substr($new_order->prod_order,0,1) == 'n')) {
                            $place = 'navNANO';
                        } else {
                            $place = 'navZAO';
                        }
                        
                        
                        $pyURL = '/home/forge/okvid.danaflex.ru/app/Helpers/soapNav.py';
                        $user  = 'nav-port-ugpc';
                        $pswd  = 'Kdf9LOkseb38&SADgb3n';
                        $py    = 'python3';
                        
                
                        switch ($place) {
                            case 'navZAO':
                                $url = 'http://sqlserver.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/ProdOrderCard';
                                break;
                            case 'navNANO':
                                $url = 'http://dnsql.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/ProdOrderCard';
                                break;
                            case 'navALABUGA':
                                $url = 'http://sql-alabuga.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/ProdOrderCard';
                                break;
                            default:
                                return 'error place code';
                                break;
                        }
                        
                        $command = "$py $pyURL -u $user -p $pswd -l $url -a prodordercard:ReadMultiple -f ".'"No:'.$new_order->prod_order.'"';
                        $output = shell_exec($command);
                
                        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $output);
                        $xml = new \SimpleXMLElement($response);
                        $json = json_encode($xml);
                            
                        $array = json_decode($json, TRUE);

                        if (array_key_exists('ProdOrderCard',$array['SoapBody']['ReadMultiple_Result']['ReadMultiple_Result'])) {

                            $array = $array['SoapBody']['ReadMultiple_Result']['ReadMultiple_Result']['ProdOrderCard'];

                            
                            $new_movement_order->printing_date = $array['Printing_Date_Last'] ?? null;
                            $new_movement_order->customer_shipment_date = $array['Date_complete'] ?? null;
                            $new_movement_order->novizna = str_replace('_', ' ', $array['novizna']) ?? null;
                            
                            if (array_key_exists('No_Previous',$array)){

                                $previos_order = Order::where('prod_order',$array['No_Previous'])->first();
                                if ($previos_order != null) {
                                    $new_movement_order->previous_engraver = $previos_order->engraving;
                                }
                                $new_movement_order->previous_order_number = $array['No_Previous']  ?? null;;
                                $new_movement_order->previous_order_printing_date = $array['Printing_Previous_Date'] ?? null;;
                            }
                            
                        }
                    }

                    $new_movement_order->okvid_number = $new_order->okvid_number;
                    $new_movement_order->order_number = $new_order->prod_order;
                    if ($new_order->request_install_date == null && $new_order->request_engraving_date == null) {
                        $new_movement_order->status = 'Заказы, у которых нет заявок/нет файлов';
                    } else if ($new_order->request_install_date != null && $new_order->request_engraving_date == null) {
                        $new_movement_order->status = 'Заказы с наличием заявки на гравировку / job ticket / валов';
                    } else if ($new_order->request_install_date != null && $new_order->request_engraving_date != null) {
                        $new_movement_order->status = 'Заказы заполнены и готовы в работу';
                    }
                    if ($macro_order->customer != null) {
                        $new_movement_order->customer = $macro_order->customer;
                    } else {
                        $new_movement_order->customer = '';
                    }
                    $new_movement_order->description = $macro_order->description;
                    $new_movement_order->shaft_quantity = $new_order->cylinder_quantity;
                    if ($shaft_request != null) {
                        $new_movement_order->transfer_document = $shaft_request->document_number;
                    }
                    $new_movement_order->technical_condition = '';
                    $new_movement_order->comment = '';
                    $new_movement_order->engraver = '';
                    $new_movement_order->priority = 0;
                    $new_movement_order->profil_engraving = $new_order->supplier_engraving;
                    $new_movement_order->repro = $new_order->supplier_engraving;
                    $new_movement_order->save();

                    $last_movement_okvid->variable_body = $new_order->okvid_number;
                    $last_movement_okvid->save();

                }
                

                }
            }
        }

    }
}
