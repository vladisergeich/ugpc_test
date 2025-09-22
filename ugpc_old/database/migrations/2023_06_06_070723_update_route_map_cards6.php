<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRouteMapCards6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_map_cards', function (Blueprint $table) {   
            $table->string('base_grinding_diameter_plan')->nullable()->change();
            $table->string('base_grinding_diameter_fact')->nullable()->change();
            $table->string('base_grinding_kromka_plan')->nullable()->change();
            $table->string('base_grinding_kromka_fact')->nullable()->change();
            $table->string('base_grinding_rz_plan')->nullable()->change();
            $table->string('base_grinding_rz_fact')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
