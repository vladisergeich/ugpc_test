<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacityLedgerEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_ledger_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->date('posting_date')->nullable();
            $table->string('work_center')->nullable();
            $table->string('document_number')->nullable();
            $table->string('description')->nullable();
            $table->decimal('quantity', 17, 5)->nullable();
            $table->string('unit_of_measure_code')->nullable();
            $table->integer('okvid_number')->nullable();
            $table->string('order_number')->nullable();
            $table->time('starting_time', $precision = 0);
            $table->time('ending_time', $precision = 0);
            $table->string('work_shift_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capacity_ledger_entries');
    }
}
