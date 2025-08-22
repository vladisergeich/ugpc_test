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
        Schema::create('regravation_approval_request_shafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regravation_approval_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('engraving_order_shaft_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('reason_id')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regravation_approval_request_shafts');
    }
};
