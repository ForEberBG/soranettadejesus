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
            if (!Schema::hasColumn('actividades', 'nombre'))
                $table->string('nombre')->nullable();
            if (!Schema::hasColumn('actividades', 'cuota'))
                $table->decimal('cuota', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropColumn(['nombre','cuota']);
        });
    }
};
