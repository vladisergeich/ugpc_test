<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouteMapStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_map_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('okvid_number')->nullable();
            $table->string('shaft_id')->nullable();
            $table->integer('stage_number')->nullable();
            $table->string('description', $precision = 0);
            $table->boolean('open')->default(false);
            $table->boolean('defect')->default(false);
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
        Schema::dropIfExists('route_map_stages');
    }
}
