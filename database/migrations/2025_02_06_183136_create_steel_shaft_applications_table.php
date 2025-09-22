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
        Schema::create('steel_shaft_applications', function (Blueprint $table) {
            $table->id();
            $table->date('create_date')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('order_number')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('engraver_id')->nullable();
            $table->integer('format')->nullable();
            $table->integer('shaft_quantity')->nullable();
            $table->integer('sleeve_length')->nullable();
            $table->integer('cilynder_length')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('status')->nullable()->default('processing');
            $table->jsonb('attachments')->nullable()->comment('Массив прикрепленных файлов');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steel_shaft_applications');
    }
};
