<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShaftResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shaft_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('string_number')->nullable();
            $table->integer('okvid_number')->nullable();
            $table->string('shaft_id')->nullable();
            $table->string('batch')->nullable();
            $table->string('edition_batch')->nullable();
            $table->date('batch_date')->nullable();
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
        Schema::dropIfExists('shaft_resources');
    }
}
