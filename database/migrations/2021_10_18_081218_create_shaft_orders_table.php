<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShaftOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shaft_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('string_number')->nullable();
            $table->integer('shaft_order_number')->nullable();
            $table->integer('okvid_number')->nullable();
            $table->string('shaft_id')->nullable();
            $table->string('note')->nullable();
            $table->date('writeoff_date')->nullable();
            $table->string('writeoff_reason')->nullable();
            $table->string('color')->nullable();
            $table->string('color_df')->nullable();
            $table->string('lineature')->nullable();
            $table->string('corner')->nullable();
            $table->string('cutter')->nullable();
            $table->string('diameter')->nullable();
            $table->string('panton')->nullable();
            $table->string('asperity')->nullable();
            $table->boolean('base')->default(false);
            $table->string('passport')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('order_status')->nullable();
            $table->string('warehouse_place')->nullable();
            $table->date('warehouse_date_dispatch')->nullable();
            $table->string('receipt_place')->nullable();
            $table->string('engraver')->nullable();
            $table->boolean('written_off')->default(false);
            $table->boolean('order_base')->default(false);
            $table->boolean('dispatch')->default(false);
            $table->string('last_batch')->nullable();
            $table->string('edition_batch')->nullable();
            $table->boolean('alab_nav')->default(false);
            $table->boolean('nano_nav')->default(false);
            $table->date('last_batch_date')->nullable();
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
        Schema::dropIfExists('shaft_orders');
    }
}
