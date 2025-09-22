<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shaft_id')->nullable();
            $table->date('document_date')->nullable();
            $table->string('executor')->nullable();
            $table->string('okvid_number')->nullable();
            $table->string('order_number')->nullable();
            $table->decimal('shaft_diameter')->nullable();
            $table->decimal('shaft_thickness')->nullable();
            $table->decimal('shaft_average_diameter')->nullable();
            $table->string('shaft_appearance')->nullable();
            $table->decimal('shaft_end_radius')->nullable();
            $table->decimal('engraving_time')->nullable();
            $table->boolean('posted')->default(false);
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
        Schema::dropIfExists('input_controls');
    }
}
