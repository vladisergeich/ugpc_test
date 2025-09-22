<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfilePassports1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_passports', function ($table) {
            $table->dropColumn('cylinder_set_number');
            $table->dropColumn('manufacturer');
            $table->dropColumn('order_number');
            $table->dropColumn('printing_machine');
            $table->dropColumn('print_type');
            $table->dropColumn('print_speed');
            $table->dropColumn('substrate');
            $table->dropColumn('material_processing _type');
            $table->dropColumn('surface_tension_material');
            $table->dropColumn('paint_series');
            $table->dropColumn('air_temp');
            $table->dropColumn('humidity');
            $table->dropColumn('note');
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
