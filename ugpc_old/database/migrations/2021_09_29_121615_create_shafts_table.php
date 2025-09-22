<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShaftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shafts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shaft_id')->nullable();
            $table->string('shaft_format')->nullable();
            $table->string('ff')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->string('note')->nullable();
            $table->boolean('free')->default(false);
            $table->string('request_number')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('warehouse')->nullable();
            $table->boolean('written_off')->default(false);
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
        Schema::dropIfExists('shafts');
    }
}
