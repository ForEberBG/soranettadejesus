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
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('tipo_comprobante')->default('factura')->after('estado_sunat');
            // factura, boleta, nota_venta
            $table->string('serie')->nullable()->after('tipo_comprobante');
            $table->string('correlativo')->nullable()->after('serie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['tipo_comprobante', 'serie', 'correlativo']);
        });
    }
};
