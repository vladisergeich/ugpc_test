<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRouteMapCards7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('route_map_cards', function (Blueprint $table) {   
        
        $table->string('dechromization_plan')->nullable()->change();
        $table->string('dechromization_fact')->nullable()->change();        
        $table->string('steel_degreasing_plan')->nullable()->change();
        $table->string('steel_degreasing_fact')->nullable()->change();        
        $table->string('pre_copper_plating_plan')->nullable()->change();
        $table->string('pre_copper_plating_fact')->nullable()->change();       
        $table->string('base_copper_plating_t_plan')->nullable()->change();
        $table->string('base_copper_plating_t_fact')->nullable()->change();
        $table->string('base_copper_plating_bath_plan')->nullable()->change();
        $table->string('base_copper_plating_bath_fact')->nullable()->change();        
        $table->string('base_degreasing_plan')->nullable()->change();
        $table->string('base_degreasing_fact')->nullable()->change();             
        $table->string('copper_plating_edition_t_plan')->nullable()->change();
        $table->string('copper_plating_edition_t_fact')->nullable()->change();
        $table->string('copper_plating_edition_bath_plan')->nullable()->change();
        $table->string('copper_plating_edition_bath_fact')->nullable()->change();     
        $table->string('grinding_edition_diameter_plan')->nullable()->change();
        $table->string('grinding_edition_diameter_fact')->nullable()->change();
        $table->string('grinding_edition_rz_plan')->nullable()->change();
        $table->string('grinding_edition_rz_fact')->nullable()->change();
        $table->string('grinding_edition_hv_plan')->nullable()->change();
        $table->string('grinding_edition_hv_fact')->nullable()->change();
        $table->string('engraving_liniatura_plan')->nullable()->change();
        $table->string('engraving_liniatura_fact')->nullable()->change();
        $table->string('engraving_number_plan')->nullable()->change();
        $table->string('engraving_number_fact')->nullable()->change();
        $table->string('engraving_degree_plan')->nullable()->change();
        $table->string('engraving_degree_fact')->nullable()->change();
        $table->string('engraving_separation_plan')->nullable()->change();
        $table->string('engraving_separation_fact')->nullable()->change();       
        $table->string('degreasing_edition_plan')->nullable()->change();
        $table->string('degreasing_edition_fact')->nullable()->change();      
        $table->string('chrome_plating_plan')->nullable()->change();
        $table->string('chrome_plating_fact')->nullable()->change();        
        $table->string('polishing_chrome_plan')->nullable()->change();
        $table->string('polishing_chrome_fact')->nullable()->change();        
        $table->string('test_print_color_plan')->nullable()->change();
        $table->string('test_print_color_fact')->nullable()->change();
       
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
