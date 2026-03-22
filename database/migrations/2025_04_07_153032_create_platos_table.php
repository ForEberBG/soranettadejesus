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
        Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 8, 2);
            $table->foreignId('categoria_id')->constrained('categorias_platos')->onDelete('cascade');
            $table->string('imagen')->nullable(); // ruta de imagen
            $table->enum('estado', ['disponible', 'no disponible'])->default('disponible'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platos');
    }
};
