<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSteelShaftApplicationCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steel_shaft_application_compositions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_number')->nullable();
            $table->string('shaft_id')->nullable();
            $table->integer('shaft_number')->nullable();
            $table->string('ff')->nullable();
            $table->float('diameter', 10, 5)->nullable();
            $table->string('shaft_ra')->nullable();
            $table->binary('shaft_rz')->nullable();
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
        Schema::dropIfExists('steel_shaft_application_compositions');
    }
}
