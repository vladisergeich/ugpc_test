<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRouteMapCards3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_map_cards', function (Blueprint $table) {
            $table->boolean('base_grinding')->default(false)->change();            
            $table->boolean('steel_degreasing')->default(false)->change();            
            $table->boolean('pre_copper_plating')->default(false)->change();           
            $table->boolean('base_copper_plating')->default(false)->change();            
            $table->boolean('base_degreasing')->default(false)->change();           
            $table->boolean('copper_plating_edition')->default(false)->change();            
            $table->boolean('grinding_edition')->default(false)->change();            
            $table->boolean('engraving')->default(false)->change();           
            $table->boolean('degreasing_edition')->default(false)->change();            
            $table->boolean('chrome_plating')->default(false)->change();           
            $table->boolean('polishing_chrome')->default(false)->change();         
            $table->boolean('test_print')->default(false)->change();

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
