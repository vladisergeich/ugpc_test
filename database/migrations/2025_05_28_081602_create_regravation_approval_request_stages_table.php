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
        Schema::create('regravation_approval_request_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sequence_number');
            $table->foreignId('regravation_approval_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('regravation_stage_id')->constrained()->onDelete('cascade');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regravation_approval_request_stages');
    }
};
