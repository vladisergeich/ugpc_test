<?php

namespace App\Console\Commands;

use App\Models\DataCollector\JobParam;
use Illuminate\Bus\Dispatcher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\GrindMachNewWorkNight;


class GrindingMachineNewWorkshiftNight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GrindingMachineNewWorkshiftNight';

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
        dispatch(new GrindMachNewWorkNight()); 

    }
}
