<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MachineDataFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'machine_data';
    }
}