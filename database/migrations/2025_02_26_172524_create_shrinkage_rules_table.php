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
        Schema::create('shrinkage_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('condition_type')->nullable();
            $table->string('condition_value')->nullable();
            $table->float('shrinkage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shrinkage_rules');
    }
};
