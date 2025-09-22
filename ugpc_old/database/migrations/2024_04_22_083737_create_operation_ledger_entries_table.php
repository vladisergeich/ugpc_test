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
        Schema::create('operation_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engraving_order_id')->nullable();
            $table->unsignedBigInteger('engraving_stage_id')->nullable();
            $table->unsignedBigInteger('operation_id');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('time_taken')->nullable();
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
        Schema::dropIfExists('operation_ledger_entries');
    }
};
