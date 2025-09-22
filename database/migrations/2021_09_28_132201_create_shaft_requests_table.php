<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShaftRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shaft_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->string('document_status')->nullable();
            $table->string('sender')->nullable();
            $table->date('shipping_date')->nullable();
            $table->string('request_creator')->nullable();
            $table->string('recipient')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('post')->default(false);
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
        Schema::dropIfExists('shaft_requests');
    }
}
