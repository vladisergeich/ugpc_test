<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayoutConstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layout_constructors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('okvid_number')->nullable();
            $table->integer('stream_number')->nullable();
            $table->integer('gp_code')->nullable();
            $table->string('gp_description')->nullable();
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
        Schema::dropIfExists('layout_constructors');
    }
}
