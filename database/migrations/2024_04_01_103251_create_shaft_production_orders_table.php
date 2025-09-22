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
        Schema::create('shaft_production_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shaft_id')->nullable();
            $table->date('create_document_date')->nullable();
            $table->integer('okvid_number')->nullable();
            $table->integer('shaft_format')->nullable();
            $table->integer('shaft_order_number')->nullable();
            $table->integer('separation_number')->nullable();
            $table->string('lineature')->nullable();
            $table->string('corner')->nullable();
            $table->string('cutter')->nullable();
            $table->string('color')->nullable();
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
        Schema::dropIfExists('shaft_production_orders');
    }
};
