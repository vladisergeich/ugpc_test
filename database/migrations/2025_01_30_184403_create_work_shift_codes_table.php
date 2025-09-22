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
        Schema::create('work_shift_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('letter')->nullable();
            $table->date('date')->nullable();
            $table->time('starting_time', $precision = 0);
            $table->time('ending_time', $precision = 0);
            $table->enum('status', ['processing', 'completed'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_shift_codes');
    }
};
