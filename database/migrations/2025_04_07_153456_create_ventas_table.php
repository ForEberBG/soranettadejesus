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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade'); // Quien registró
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->enum('tipo', ['mesa', 'llevar', 'delivery']);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'qr'])->default('efectivo');
            $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
