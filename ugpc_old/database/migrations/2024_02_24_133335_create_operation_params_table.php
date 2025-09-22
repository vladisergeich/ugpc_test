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
        Schema::create('operation_params', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable();
            $table->integer('okvid_number')->nullable();
            $table->string('shaft_id')->nullable();
            $table->string('operation')->nullable();
            $table->string('parameter')->nullable();
            $table->decimal('value', 17, 5)->nullable();
            $table->string('unit_of_measure_code')->nullable();
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
        Schema::dropIfExists('operation_params');
    }
};
