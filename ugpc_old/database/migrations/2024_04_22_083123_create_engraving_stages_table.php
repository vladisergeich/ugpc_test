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
        Schema::create('engraving_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('engraving_order_stage_id');
            $table->unsignedBigInteger('stage_id');
            $table->boolean('open')->default(false);
            $table->boolean('post')->default(false);
            $table->boolean('defect')->default(false);
            $table->text('defect_note')->nullable();
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
        Schema::dropIfExists('engraving_stages');
    }
};
