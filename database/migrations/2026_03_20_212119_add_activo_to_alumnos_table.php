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
        Schema::table('alumnos', function (Blueprint $table) {
            if (!Schema::hasColumn('alumnos', 'apellidos'))
                $table->string('apellidos')->nullable();
            if (!Schema::hasColumn('alumnos', 'nombres'))
                $table->string('nombres')->nullable();
            if (!Schema::hasColumn('alumnos', 'dni'))
                $table->string('dni', 8)->nullable();
            if (!Schema::hasColumn('alumnos', 'seccion'))
                $table->string('seccion', 2)->default('C')->nullable();
            if (!Schema::hasColumn('alumnos', 'apoderado'))
                $table->string('apoderado')->nullable();
            if (!Schema::hasColumn('alumnos', 'celular'))
                $table->string('celular', 20)->nullable();
            if (!Schema::hasColumn('alumnos', 'parentesco'))
                $table->string('parentesco')->default('Madre')->nullable();
            if (!Schema::hasColumn('alumnos', 'observaciones'))
                $table->text('observaciones')->nullable();
            if (!Schema::hasColumn('alumnos', 'activo'))
                $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn(['apellidos','nombres','dni','seccion',
                                'apoderado','celular','parentesco',
                                'observaciones','activo']);
        });
    }
};
