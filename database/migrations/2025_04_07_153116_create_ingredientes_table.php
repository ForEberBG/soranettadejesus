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
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('unidad'); // kg, lt, unidad, etc.
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredientes');
    }
};
