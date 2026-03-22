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
        Schema::table('gastos', function (Blueprint $table) {
            if (!Schema::hasColumn('gastos', 'descripcion'))
                $table->string('descripcion')->nullable();
            if (!Schema::hasColumn('gastos', 'monto'))
                $table->decimal('monto', 8, 2)->default(0);
            if (!Schema::hasColumn('gastos', 'categoria'))
                $table->string('categoria')->default('Material');
            if (!Schema::hasColumn('gastos', 'actividad_id'))
                $table->foreignId('actividad_id')->nullable()->constrained('actividades')->nullOnDelete();
            if (!Schema::hasColumn('gastos', 'fecha'))
                $table->date('fecha')->nullable();
            if (!Schema::hasColumn('gastos', 'comprobante'))
                $table->string('comprobante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropColumn(['descripcion','monto','categoria',
                                'actividad_id','fecha','comprobante']);
        });
    }
};
