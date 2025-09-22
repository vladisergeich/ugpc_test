<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMacroOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('macro_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('macro_id')->nullable();
            $table->string('description')->nullable();
            $table->boolean('written_off')->default(false);
            $table->date('create_date')->nullable();
            $table->integer('section_qty')->nullable();
            $table->string('note')->nullable();
            $table->string('crosses_bleed')->nullable();
            $table->boolean('rotate_factor')->default(false);
            $table->boolean('bez_reza')->default(false);
            $table->boolean('cold_seal')->default(false);
            $table->boolean('auto_offset')->default(false);
            $table->boolean('relsa')->default(false);
            $table->boolean('shetnechet')->default(false);
            $table->string('customer')->nullable();
            $table->boolean('reservation')->default(false);
            $table->boolean('zazor')->default(false);
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
        Schema::dropIfExists('macro_orders');
    }
}
