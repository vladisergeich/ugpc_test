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
        Schema::create('steel_shaft_application_shafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('steel_shaft_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('shaft_id')->constrained()->onDelete('cascade');
            $table->integer('sequence')->unsigned();
            $table->enum('ff', ['SC', 'HS','RM'])->default('HS');
            $table->string('shaft_ra')->nullable();
            $table->string('shaft_rz')->nullable();
            $table->float('diameter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steel_shaft_application_shafts');
    }
};
