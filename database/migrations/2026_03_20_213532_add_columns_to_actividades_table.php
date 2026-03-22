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
        Schema::table('actividades', function (Blueprint $table) {
            if (!Schema::hasColumn('actividades', 'activo'))
                $table->boolean('activo')->default(true);
            if (!Schema::hasColumn('actividades', 'descripcion'))
                $table->text('descripcion')->nullable();
            if (!Schema::hasColumn('actividades', 'fecha_limite'))
                $table->date('fecha_limite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropColumn(['activo','descripcion','fecha_limite']);
        });
    }
};
