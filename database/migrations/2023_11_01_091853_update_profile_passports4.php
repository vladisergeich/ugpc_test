<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProfilePassports4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_passports', function ($table) {
            $table->decimal('optical_density_admission', 17, 5)->nullable();
            $table->decimal('raster_tone_50_admission', 17, 5)->nullable();
            $table->decimal('raster_tone_15_admission', 17, 5)->nullable();
            $table->decimal('raster_tone_5_admission', 17, 5)->nullable();
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
