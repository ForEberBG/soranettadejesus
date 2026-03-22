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
            if (!Schema::hasColumn('cobros', 'metodo_pago'))
                $table->enum('metodo_pago', ['efectivo','yape','plin','otro'])
                      ->default('efectivo')->after('observaciones');
            if (!Schema::hasColumn('cobros', 'captura'))
                $table->string('captura')->nullable()->after('metodo_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->dropColumn(['metodo_pago','captura']);
        });
    }
};
