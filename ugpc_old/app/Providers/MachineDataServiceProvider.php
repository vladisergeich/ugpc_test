<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MachineDataService;

class MachineDataServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('machine_data', function ($app) {
            return new MachineDataService();
        });
    }

    public function boot()
    {
        //
    }
}