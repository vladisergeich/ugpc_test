<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile_print_process_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->integer('sequence')->unsigned();
            $table->string('color')->nullable();
            $table->float('viscosity')->nullable();
            $table->string('solvent')->nullable();
            $table->jsonb('inhibitor')->default(json_encode([
                'value' => null,
                'percentage' => null,
            ]));
            $table->string('coordinates_lab')->nullable();
            $table->float('eltex_value')->nullable();
            $table->string('raquel')->nullable();
            $table->jsonb('optical_density')->default(json_encode([
                'value' => null,
                'admission' => null,
            ]));
            $table->jsonb('raster_tone_50')->default(json_encode([
                'value' => null,
                'admission' => null,
            ]));
            $table->jsonb('raster_tone_15')->default(json_encode([
                'value' => null,
                'admission' => null,
            ]));
            $table->jsonb('raster_tone_5')->default(json_encode([
                'value' => null,
                'admission' => null,
            ]));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_print_process_colors');
    }
};
