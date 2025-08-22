<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movement_orders', function (Blueprint $table) {
            $table->unsignedInteger('quantity_shaft')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('movement_orders', function (Blueprint $table) {
            $table->dropColumn('quantity_shaft');
        });
    }
}; 