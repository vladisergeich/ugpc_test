<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ugpc\WorkShiftCode;
use App\Models\Ugpc\CapacityLedgerEntry;

class GrindMachNewWorkNight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $last_work_shift_code = WorkShiftCode::where('open',true)->first();
        $last_work_shift_code->open = false;
        $last_work_shift_code->post = true;

        $last_work_shift_code->save();

        $new_work_shift_code = WorkShiftCode::where('id',$last_work_shift_code->id+1)->first();
        $new_work_shift_code->open = true;
        $new_work_shift_code->save();
    }
}
