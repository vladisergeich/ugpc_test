<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRouteMapCards2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_map_cards', function ($table) {
            $table->boolean('defect')->default(false);
            $table->boolean('dechromization_defect')->default(false);
            $table->boolean('base_grinding_defect')->default(false);
            $table->boolean('steel_degreasing_defect')->default(false);
            $table->boolean('pre_copper_plating_defect')->default(false);
            $table->boolean('base_copper_plating_defect')->default(false);
            $table->boolean('base_degreasing_defect')->default(false);
            $table->boolean('copper_plating_edition_defect')->default(false);
            $table->boolean('grinding_edition_defect')->default(false);
            $table->boolean('engraving_defect')->default(false);
            $table->boolean('degreasing_edition_defect')->default(false);
            $table->boolean('chrome_plating_defect')->default(false);
            $table->boolean('polishing_chrome_defect')->default(false);
            $table->boolean('test_print_defect')->default(false);
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
