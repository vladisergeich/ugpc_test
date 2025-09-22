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
        Schema::table('operation_ledger_entries', function ($table) {
            $table->integer('okvid_number')->nullable();
            $table->time('starting_time', $precision = 0);
            $table->time('ending_time', $precision = 0);
            $table->decimal('quantity', 17, 5)->nullable();
            $table->string('unit_of_measure_code')->nullable();
            $table->string('operator')->nullable();
            $table->dropColumn('entry_type');
            $table->dropColumn('location_code');
        });
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
