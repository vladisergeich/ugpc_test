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
        Schema::create('re_engraving_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('macro_order_id');
            $table->string('order_number')->nullable();
            $table->string('order_desc')->nullable();
            $table->string('novizna')->nullable();
            $table->string('place')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('customer_desc')->nullable();
            $table->string('status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('print_date')->nullable();
            $table->float('planned_footage')->nullable();
            $table->float('actual_footage')->nullable();
            $table->string('machine_center')->nullable();
            $table->string('engraver')->nullable();
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
        Schema::dropIfExists('re_engraving_applications');
    }
};
