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
use Carbon\Carbon;


class PrintDateUpdate implements ShouldQueue
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

        $order = Order::where('okvid_number',$this->shaft->okvid_number)->first();
        
        foreach ($places as $place) {

            switch ($place) {
                case 'navZAO':
                    $url = 'http://10.10.40.107:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/ParamOkvidPage';
                    break;
                case 'navNANO':
                    $url = 'http://10.10.40.78:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/ParamOkvidPage';
                    break;
                case 'navALABUGA':
                    $url = 'http://10.5.128.144:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/ParamOkvidPage';
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
                        'Field' => 'Design_No',
                        'Criteria' => "$order->upak_order",
                        ],
                    ],
                    'setSize' => 30,
                ) 
            )->{'ReadMultiple_Result'};

            if (!empty($result->{'ParamOkvidPage'})) {

                $result = $result->{'ParamOkvidPage'};

                if (!is_array($result)) {
                    $result = [$result];
                }
            
                foreach ($result as $item) {
                    if (is_object($item)) {
                        if ($item->PrintingDate > $order->printing_date) {
                            $order->printing_date = $item->PrintingDate;
                            $order->save();
                        }
                    }
                }

            }
        }

    }
}
