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
        Schema::create('ingrediente_plato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plato_id')->constrained('platos')->onDelete('cascade');
            $table->foreignId('ingrediente_id')->constrained('ingredientes')->onDelete('cascade');
            $table->decimal('cantidad_usada', 10, 2); // Cuánto se usa de ese ingrediente por plato
            $table->timestamps();

            $table->unique(['plato_id', 'ingrediente_id']); // Evita duplicados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingrediente_plato');
    }
};
