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
        Schema::create('shaft_consumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engraving_order_shaft_id')->nullable();
            $table->unsignedBigInteger('machine_center_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shaft_consumptions');
    }
};
