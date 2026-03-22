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
        Schema::table('cobros', function (Blueprint $table) {
           if (!Schema::hasColumn('cobros', 'alumno_id'))
                $table->foreignId('alumno_id')->nullable()->constrained('alumnos');
            if (!Schema::hasColumn('cobros', 'actividad_id'))
                $table->foreignId('actividad_id')->nullable()->constrained('actividades');
            if (!Schema::hasColumn('cobros', 'monto'))
                $table->decimal('monto', 8, 2)->default(0);
            if (!Schema::hasColumn('cobros', 'fecha'))
                $table->date('fecha')->nullable();
            if (!Schema::hasColumn('cobros', 'observaciones'))
                $table->string('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->dropColumn(['alumno_id','actividad_id','monto','fecha','observaciones']);
        });
    }
};
