<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->decimal('monto_inicial', 10, 2)->default(0);
            $table->decimal('total_efectivo', 10, 2)->default(0);
            $table->decimal('total_yape', 10, 2)->default(0);
            $table->decimal('total_plin', 10, 2)->default(0);
            $table->decimal('total_tarjeta', 10, 2)->default(0);
            $table->decimal('total_ventas', 10, 2)->default(0);
            $table->integer('num_ventas')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamp('apertura_at');
            $table->timestamp('cierre_at')->nullable();
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
