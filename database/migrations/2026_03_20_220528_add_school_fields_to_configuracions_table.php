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
        Schema::table('configuracions', function (Blueprint $table) {
            if (!Schema::hasColumn('configuracions', 'aula'))
                $table->string('aula')->default('4to "C"');
            if (!Schema::hasColumn('configuracions', 'docente'))
                $table->string('docente')->nullable();
            if (!Schema::hasColumn('configuracions', 'anio_escolar'))
                $table->string('anio_escolar')->default(date('Y'));
            if (!Schema::hasColumn('configuracions', 'turno'))
                $table->string('turno')->default('Mañana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracions', function (Blueprint $table) {
            $table->dropColumn(['aula','docente','anio_escolar','turno']);
        });
    }
};
