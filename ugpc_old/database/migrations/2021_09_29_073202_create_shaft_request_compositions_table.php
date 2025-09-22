<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShaftRequestCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shaft_request_compositions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->integer('line_number')->nullable();
            $table->integer('okvid_number')->nullable();
            $table->string('shaft_id')->nullable();
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
        Schema::dropIfExists('shaft_request_compositions');
    }
}
