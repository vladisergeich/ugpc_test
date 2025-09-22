<?php

namespace App\Console\Commands;

use App\Models\DataCollector\JobParam;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\MovementOrdersNewData;
use App\Jobs\MovementOrdersUpdate;
use App\Jobs\ReEngravingAppUpdate;
use App\Models\Ugpc\MovementOrder;
use App\Models\Ugpc\ReEngravingApplication;
use Illuminate\Support\Carbon;


class MovementOrdersData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MovementOrdersData';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = MovementOrder::where('movement_orders.okvid_number', 'not like', '3633%')
    ->join('shaft_orders', 'shaft_orders.okvid_number', '=', 'movement_orders.okvid_number')
    ->where('shaft_orders.written_off', false)
    ->orderBy('movement_orders.completion_date', 'desc')
    ->groupBy([
        'movement_orders.okvid_number',  // Группируем сначала по этому полю
        'movement_orders.order_number'   // Затем по order_number (NULL-значения будут отдельными)
    ])
    ->selectRaw('
        shaft_orders.okvid_number,
        shaft_orders.written_off,
        movement_orders.*,
        MAX(movement_orders.completion_date) as completion_date
    ')
    ->get();

        foreach ($orders as $order) {
            dispatch(new MovementOrdersUpdate(['order'=>$order])); 
        }

        /*
        $apps = ReEngravingApplication::where('status','<>','Выполнено')->get();

        foreach ($apps as $app) {
            dispatch(new ReEngravingAppUpdate(['app'=> $app])); 
        }
            */

    }
}
