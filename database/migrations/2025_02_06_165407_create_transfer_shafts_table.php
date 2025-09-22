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
        Schema::create('transfer_shafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained()->onDelete('cascade');
            $table->foreignId('engraving_order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('shaft_id')->constrained()->onDelete('cascade');
            $table->integer('sequence')->unsigned();
            $table->string('type')->nullable()->default('engraving');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_shafts');
    }
};
