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
        Schema::create('pinting_process_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->integer('string_number')->nullable();
            $table->string('color')->nullable();
            $table->float('paint_viscosity')->nullable();
            $table->string('solvent')->nullable();
            $table->string('inhibitor')->nullable();
            $table->float('percentage_inhibitor')->nullable();
            $table->string('coordinates_lab')->nullable();
            $table->float('optical_density')->nullable();
            $table->float('raster_tone_50')->nullable();
            $table->float('raster_tone_15')->nullable();
            $table->float('raster_tone_5')->nullable();
            $table->float('optical_density_admission')->nullable();
            $table->float('raster_tone_50_admission')->nullable();
            $table->float('raster_tone_15_admission')->nullable();
            $table->float('raster_tone_5_admission')->nullable();
            $table->float('eltex_value')->nullable();
            $table->string('raquel')->nullable();
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
        Schema::dropIfExists('pinting_process_parameters');
    }
};
