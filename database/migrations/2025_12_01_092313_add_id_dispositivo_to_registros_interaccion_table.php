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
        Schema::table('registros_interaccion', function (Blueprint $table) {
            $table->string('ID_dispositivo')->nullable()->after('empleado_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_interaccion', function (Blueprint $table) {
            $table->dropColumn('ID_dispositivo');
        });
    }
};
