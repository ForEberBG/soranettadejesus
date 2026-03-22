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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'rol'))
                $table->enum('rol', ['administrador','docente','tesorero','padre'])
                      ->default('docente')->after('name');
            if (!Schema::hasColumn('users', 'activo'))
                $table->boolean('activo')->default(true)->after('rol');
            if (!Schema::hasColumn('users', 'alumno_id'))
                $table->foreignId('alumno_id')->nullable()
                      ->constrained('alumnos')->nullOnDelete()->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropColumn(['rol','activo','alumno_id']);
        });
    }
};
