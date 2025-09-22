<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AccessToken;

class ClearTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature = 'tokens:clear';
    protected $description = 'Clear active tokens and log out users';
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
        AccessToken::where('expires_at', '<', now())->delete();
    }
}
