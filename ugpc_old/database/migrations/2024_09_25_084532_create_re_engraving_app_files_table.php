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
        Schema::create('re_engraving_app_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('shaft_id');
            $table->string('filename'); 
            $table->string('path');
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
        Schema::dropIfExists('re_engraving_app_files');
    }
};
