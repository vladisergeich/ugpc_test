<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fingerprint_parameters', function (Blueprint $table) {
            $table->id();
            $table->integer('string_number')->nullable();
            $table->string('color')->nullable();
            $table->float('anilox_lineature')->nullable();
            $table->float('cell_volume')->nullable();
            $table->float('lineature')->nullable();
            $table->string('tape_type')->nullable();
            $table->string('pigment_paste')->nullable();
            $table->string('technical_lacquer')->nullable();
            $table->string('base_lacquer')->nullable();
            $table->float('corner')->nullable();
            $table->float('cutter')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fingerprint_parameters');
    }
};
