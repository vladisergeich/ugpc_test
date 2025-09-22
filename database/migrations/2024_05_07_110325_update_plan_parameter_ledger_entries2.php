<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('plan_parameter_ledger_entries', function ($table) {
            $table->unsignedBigInteger('engraving_stage_id')->foreignId()
                ->constrained('engraving_stages')
                ->onDelete('cascade')->change();
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
