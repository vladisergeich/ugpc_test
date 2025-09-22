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
        Schema::table('operation_ledger_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('posting_date')->nullable();
            $table->time('start_time')->change();
            $table->time('end_time')->change();
            $table->string('work_shift_code')->nullable();
            $table->unsignedBigInteger('work_center_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
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
