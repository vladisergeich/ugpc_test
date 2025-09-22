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
        
        Schema::create('movement_orders', function (Blueprint $table) {
            $table->id();
            $table->date('completion_date')->nullable();
            $table->unsignedSmallInteger('priority_number')->default(0);
            $table->nullableMorphs('related'); // related_type, related_id
            $table->unsignedBigInteger('engraver_id')->nullable();
            $table->string('type')->default('order'); // order, downtime, request
            $table->string('status')->default('await')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_orders');
    }
};
