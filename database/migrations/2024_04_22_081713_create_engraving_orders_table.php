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
        Schema::create('engraving_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('design_order_id');
            $table->unsignedBigInteger('shaft_id');
            $table->string('format')->nullable();
            $table->string('initial_size')->nullable();
            $table->string('final_size')->nullable();
            $table->string('status')->nullable();
            $table->string('shaft_number')->nullable();
            $table->string('separation_number')->nullable();
            $table->string('lineature')->nullable();
            $table->string('corner')->nullable();
            $table->string('cutter')->nullable();
            $table->string('color')->nullable();
            $table->text('defect_comment')->nullable();
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
        Schema::dropIfExists('engraving_orders');
    }
};
