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
        Schema::create('shaft_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engraving_order_shaft_id')->nullable();
            $table->integer('sequence')->unsigned();
            $table->string('order_number')->nullable();
            $table->date('print_date')->nullable();
            $table->float('footage')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shaft_resources');
    }
};
