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
        Schema::table('item_ledger_entries', function ($table) {
            $table->string('type')->nullable();
            $table->integer('item_number')->nullable();
            $table->date('posting_date')->nullable();
            $table->string('shaft_id')->nullable();
            $table->string('document_number')->nullable();
            $table->string('order_number')->nullable();
            $table->string('work_shift_code')->nullable();
            $table->string('work_center')->nullable();
            $table->string('machine')->nullable();
            $table->string('description')->nullable();
            $table->decimal('quantity', 17, 5)->nullable();
            $table->string('unit_of_measure_code')->nullable();
            $table->boolean('open')->default(false);
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
