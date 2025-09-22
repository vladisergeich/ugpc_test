<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfiles3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profiles', function ($table) {
            $table->string('cylinder_set_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('order_number')->nullable();
            $table->string('printing_machine')->nullable();
            $table->string('print_type')->nullable();
            $table->string('print_speed')->nullable();
            $table->string('substrate')->nullable();
            $table->string('material_processing _type')->nullable();
            $table->string('surface_tension_material')->nullable();
            $table->string('vendor_paint_series')->nullable();
            $table->string('air_temp')->nullable();
            $table->string('humidity')->nullable();
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
