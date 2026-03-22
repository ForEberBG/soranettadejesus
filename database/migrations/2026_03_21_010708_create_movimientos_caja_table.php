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
        Schema::create('movimientos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_chica_id')->constrained('caja_chica')->cascadeOnDelete();
            $table->enum('tipo', ['egreso','reposicion']);
            $table->string('descripcion');
            $table->decimal('monto', 8, 2);
            $table->string('categoria')->default('General');
            $table->string('comprobante')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_caja');
    }
};
