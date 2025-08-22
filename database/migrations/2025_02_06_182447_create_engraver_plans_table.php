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
        Schema::create('engraver_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engraver_id')->nullable();
            $table->enum('type', ['monthly', 'daily']); 
            $table->date('date')->nullable(); 
            $table->integer('year')->nullable(); 
            $table->integer('month')->nullable(); 
            $table->integer('planned_shafts'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engraver_plans');
    }
};
