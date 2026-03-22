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
        Schema::create('caja_chica', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto_inicial', 8, 2)->default(0);
            $table->decimal('saldo_actual',  8, 2)->default(0);
            $table->enum('estado', ['abierta','cerrada'])->default('abierta');
            $table->string('descripcion')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('fecha_apertura')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_chica');
    }
};
