<?php

namespace App\Console\Commands;

use App\Models\DataCollector\JobParam;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\PrintDateUpdate;
use App\Jobs\WarehouseUpdate;
use App\Models\BDGP\ShaftOrder;


class OrderPrintingDateUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'OrderPrintingDateUpdate';

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
        $shafts = ShaftOrder::where('written_off',false)
        ->where('shaft_id','<>',0)
        ->groupBy('okvid_number')
        ->get();

        foreach ($shafts as $shaft) {
            dispatch(new PrintDateUpdate(['shaft'=>$shaft])); 
            dispatch(new WarehouseUpdate(['shaft'=>$shaft])); 
        }
    }
}
