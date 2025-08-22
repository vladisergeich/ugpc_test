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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedSmallInteger('subspecies')->default(0);
            $table->string('reduction')->nullable();
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->string('customer')->nullable();
            $table->string('brand')->nullable();
            $table->string('sap_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
