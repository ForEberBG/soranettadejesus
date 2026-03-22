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
        Schema::table('asistencias', function (Blueprint $table) {
             if (!Schema::hasColumn('asistencias', 'reunion_id'))
                $table->foreignId('reunion_id')->nullable()
                      ->constrained('reuniones')->cascadeOnDelete();
            if (!Schema::hasColumn('asistencias', 'alumno_id'))
                $table->foreignId('alumno_id')->nullable()
                      ->constrained('alumnos')->cascadeOnDelete();
            if (!Schema::hasColumn('asistencias', 'asistio'))
                $table->boolean('asistio')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn(['reunion_id','alumno_id','asistio']);
        });
    }
};
