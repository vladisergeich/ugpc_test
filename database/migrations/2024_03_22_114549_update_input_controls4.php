<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('input_controls', function ($table) {
            $table->integer('kit_number')->nullable();
            $table->integer('separation_number')->nullable();
            $table->dropColumn('executor');
            $table->dropColumn('shaft_thickness');
            $table->dropColumn('shaft_average_diameter');
            $table->dropColumn('shaft_end_radius');
            $table->dropColumn('shaft_appearance');
            $table->dropColumn('edge_radius');
            $table->dropColumn('roughness');
            $table->dropColumn('input_control_defect');
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
};
