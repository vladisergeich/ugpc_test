<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('okvid_number')->nullable();
            $table->string('order_number')->nullable();
            $table->string('status')->nullable();
            $table->string('customer')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('description')->nullable();
            $table->integer('shaft_quantity')->nullable();
            $table->date('printing_date')->nullable();
            $table->string('transfer_document')->nullable();
            $table->string('technical_condition')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('movement_orders');
    }
}
