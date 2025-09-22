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
        Schema::create('profile_fingerprint_colors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->integer('sequence')->unsigned();
            $table->string('color')->nullable();
            $table->string('pigment_paste')->nullable();
            $table->string('technical_lacquer')->nullable();
            $table->string('base_lacquer')->nullable();
            $table->float('lineature')->nullable();
            $table->float('corner')->nullable();
            $table->float('cutter')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_fingerprint_colors');
    }
};
