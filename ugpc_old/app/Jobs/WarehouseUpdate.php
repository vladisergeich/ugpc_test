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
use App\Models\BDGP\ShaftOrder;
use Carbon\Carbon;


class WarehouseUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 200;
    public $shaft;
    /**

     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->shaft = $data['shaft'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $places = ['navALABUGA','navNANO','navZAO'];

        foreach ($places as $place) {

            switch ($place) {
                case 'navZAO':
                    $url = 'http://10.10.40.107:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/ComplectClishePage';
                    break;
                case 'navNANO':
                    $url = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/ComplectClishePage';
                    break;
                case 'navALABUGA':
                    $url = 'http://10.5.128.144:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/ComplectClishePage';
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
        
            $okvid = substr($this->shaft->okvid_number, 0, strlen($this->shaft->okvid_number) - 2) . '-' . substr($this->shaft->okvid_number, -2);
            $result = $client->ReadMultiple(
                array( 
                    'filter'=>[ 
                        [
                        'Field' => 'okvid_no',
                        'Criteria' => "$okvid",
                        ],
                    ],
                    'setSize' => 1,
                ) 
            )->{'ReadMultiple_Result'};

            if (!empty($result->{'ComplectClishePage'})) {
                //$result = $result->{'ComplectClishePage'};

                $order = ShaftOrder::where('shaft_orders.okvid_number', $this->shaft->okvid_number)
                ->where('shaft_orders.shaft_id', '<>', 0)
                ->where('shaft_orders.written_off', false)
                ->join('shafts', 'shafts.shaft_id', '=', 'shaft_orders.shaft_id') // Добавляем связь с shaft
                ->groupBy('shaft_orders.okvid_number')
                ->selectRaw('
                    shaft_orders.shaft_id, 
                    shaft_orders.okvid_number, 
                    shaft_orders.written_off,
                    shafts.shaft_id, 
                    shafts.warehouse,
                    CASE 
                        WHEN COUNT(DISTINCT shafts.warehouse) = 1 
                        THEN MAX(shafts.warehouse) 
                        ELSE "Смешанный" 
                    END as warehouse
                ')
                ->first();
                
                $result->ComplectClishePage->LocCode = $order->warehouse ?? '';
            
                $object_nav = new \stdClass;
                $object_nav->ComplectClishePage_List = $result;
                $client->UpdateMultiple($object_nav);
            }
        }

    }
}
