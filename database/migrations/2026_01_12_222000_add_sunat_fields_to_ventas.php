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
           $table->string('xml_path')->nullable();
        $table->string('pdf_path')->nullable();
        $table->string('cdr_path')->nullable();
        $table->enum('estado_sunat', ['pendiente','enviado','aceptado','rechazado'])->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            //
        });
    }
};
