<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_passports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('profile_number')->nullable();
            $table->string('parameter _type')->nullable();
            $table->string('cylinder_set_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('order_number')->nullable();
            $table->string('printing_machine')->nullable();
            $table->string('print_type')->nullable();
            $table->string('print_speed')->nullable();
            $table->string('substrate')->nullable();
            $table->string('material_processing _type')->nullable();
            $table->string('surface_tension_material')->nullable();
            $table->string('paint_series')->nullable();
            $table->string('air_temp')->nullable();
            $table->string('humidity')->nullable();
            $table->integer('string_number')->nullable();
            $table->string('paint')->nullable();
            $table->string('pigment_paste')->nullable();
            $table->string('technical_lacquer')->nullable();
            $table->string('base_lacquer')->nullable();
            $table->decimal('lineature', 17, 5)->nullable();
            $table->decimal('corner', 17, 5)->nullable();
            $table->decimal('cutter', 17, 5)->nullable();
            $table->string('fingerprint_color_note')->nullable();
            $table->string('color')->nullable();
            $table->string('paint_viscosity')->nullable();
            $table->string('solvent_used')->nullable();
            $table->decimal('inhibitor', 17, 5)->nullable();
            $table->decimal('eltex_value', 17, 5)->nullable();
            $table->string('raquel')->nullable();
            $table->decimal('optical_density', 17, 5)->nullable();
            $table->decimal('raster_tone_50', 17, 5)->nullable();
            $table->decimal('raster_tone_15', 17, 5)->nullable();
            $table->decimal('raster_tone_5', 17, 5)->nullable();
            $table->string('note')->nullable();
            $table->string('user')->nullable();
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
        Schema::dropIfExists('profile_passports');
    }
}
