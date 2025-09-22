<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_number')->nullable();
            $table->string('gp_code')->nullable();
            $table->string('okvid_number')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('orientation')->nullable();
            $table->string('note')->nullable();
            $table->integer('quantity')->nullable();       
            $table->date('agreement_date')->nullable();
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
        Schema::dropIfExists('order_streams');
    }
}
