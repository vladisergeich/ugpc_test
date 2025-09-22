<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRouteMapCards11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('route_map_cards', function ($table) {
            $table->boolean('dechromization_post')->default(false);
            $table->boolean('base_grinding_post')->default(false);
            $table->boolean('steel_degreasing_post')->default(false);
            $table->boolean('pre_copper_plating_post')->default(false);
            $table->boolean('base_copper_plating_post')->default(false);
            $table->boolean('base_degreasing_post')->default(false);
            $table->boolean('copper_plating_edition_post')->default(false);
            $table->boolean('grinding_edition_post')->default(false);
            $table->boolean('engraving_post')->default(false);
            $table->boolean('degreasing_edition_post')->default(false);
            $table->boolean('chrome_plating_post')->default(false);
            $table->boolean('polishing_chrome_post')->default(false);
            $table->boolean('test_print_post')->default(false);
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
