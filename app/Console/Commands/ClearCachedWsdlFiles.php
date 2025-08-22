<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCachedWsdlFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear_cached_wsdl_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached wsdl files from SoapClient';

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
        array_map('unlink', glob(sys_get_temp_dir() . DIRECTORY_SEPARATOR . "*.wsdl"));

        return 0;
    }
}
