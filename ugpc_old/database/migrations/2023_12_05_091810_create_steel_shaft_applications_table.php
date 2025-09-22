<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSteelShaftApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steel_shaft_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->string('manager')->nullable();
            $table->string('okvid_number')->nullable();
            $table->string('order_number')->nullable();
            $table->string('vendor')->nullable();
            $table->string('engraver')->nullable();
            $table->integer('format')->nullable();
            $table->integer('shaft_quantity')->nullable();
            $table->integer('sleeve_length')->nullable();
            $table->integer('shaft_length')->nullable();
            $table->string('comment')->nullable();
            $table->binary('pdf_file')->nullable();
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
        Schema::dropIfExists('steel_shaft_applications');
    }
}
