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
        Schema::create('shafts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->float('format')->nullable();
            $table->string('ff')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('status')->default('normal')->nullable();
            $table->string('state')->default('free')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('warehouse_place')->nullable();
            $table->string('type')->default('steel');
            $table->float('thickness')->nullable();
            $table->float('diameter')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shafts');
    }
};
