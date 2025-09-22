<?php

namespace App\Listeners;

use App\Events\GrinderWarehouse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GrinderWarehouseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\GrinderWarehouse  $event
     * @return void
     */
    public function handle(GrinderWarehouse $event)
    {
        //
    }
}
