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
        Schema::table('reuniones', function (Blueprint $table) {
            if (!Schema::hasColumn('reuniones', 'tema'))
                $table->string('tema')->nullable();
            if (!Schema::hasColumn('reuniones', 'fecha'))
                $table->date('fecha')->nullable();
            if (!Schema::hasColumn('reuniones', 'hora'))
                $table->time('hora')->nullable();
            if (!Schema::hasColumn('reuniones', 'lugar'))
                $table->string('lugar')->nullable();
            if (!Schema::hasColumn('reuniones', 'notas'))
                $table->text('notas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reuniones', function (Blueprint $table) {
            $table->dropColumn(['tema','fecha','hora','lugar','notas']);
        });
    }
};
