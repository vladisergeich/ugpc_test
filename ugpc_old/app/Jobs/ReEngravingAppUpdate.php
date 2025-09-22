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
use App\Models\Ugpc\ReEngravingApplication;
use Carbon\Carbon;


class ReEngravingAppUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 800;
    public $app;
    /**

     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->app = $data['app'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $place = null;
    
        $app = ReEngravingApplication::find($this->app->id);

        if ($app->order_number != null) {
            
            if ((mb_substr($app->order_number,0,1) == 'A') || (mb_substr($app->order_number,0,1) == 'a')){
                $place = 'navALABUGA';
            } else if ((mb_substr($app->order_number,0,1) == 'N') || (mb_substr($app->order_number,0,1) == 'n')) {
                $place = 'navNANO';
            } else {
                $place = 'navZAO';
            }
            
            $options = [
                'login' => 'nav-port-ugpc',
                'password' => 'Kdf9LOkseb38&SADgb3n'
            ];
    
            switch ($place) {
                case 'navZAO':
                    $url = 'http://sqlserver.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%97%D0%90%D0%9E%20%22%D0%94%D0%B0%D0%BD%D0%B0%D1%84%D0%BB%D0%B5%D0%BA%D1%81%22/Page/MovementOrdersPage';
                    break;
                case 'navNANO':
                    $url = 'http://dnsql.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%9D%D0%90%D0%9D%D0%9E%22/Page/MovementOrdersPage';
                    break;
                case 'navALABUGA':
                    $url = 'http://sql-alabuga.danaflex.local:7047/MicrosoftDynamicsNavServer/WS/%D0%9E%D0%9E%D0%9E%20%22%D0%94%D0%90%D0%9D%D0%90%D0%A4%D0%9B%D0%95%D0%9A%D0%A1-%D0%90%D0%9B%D0%90%D0%91%D0%A3%D0%93%D0%90%22/Page/MovementOrdersPage';
                    break;
                default:
                    return 'error place code';
                    break;
            }
            
            $client = new SoapClient($url,$options);
            $result = $client->ReadMultiple(
                array( 
                    'filter'=>[ 
                        [
                        'Field' => 'PO',
                        'Criteria' => "'{$app->order_number}'",
                        ],
                        [
                        'Field' => 'OperationType',
                        'Criteria' => "Глубокая печать|Флексопечать",
                        ],
                        [
                        'Field' => 'Order_Snyat',
                        'Criteria' => "false",
                        ],
                    ],
                    'setSize' => -1
                ) 
            )->{'ReadMultiple_Result'};

            if (!empty($result->{'MovementOrdersPage'})) {
                $order = $result->{'MovementOrdersPage'};

                $app->print_date = $order->Starting_Date_x002C__Fakt ?? null;
                $app->planned_footage = $order->Metraz ?? null;
                $app->actual_footage = $order->Volume_x002C__m ?? null;
                $app->machine_center = $order->MachineCenter ?? null;
                $app->save();
            }
            
        }
        
    }
}
