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
        Schema::table('fletes', function (Blueprint $table) {
            // Agregar cliente_id como foreign key nullable
            $table->unsignedBigInteger('cliente_id')->nullable()->after('id');
            $table->foreign('cliente_id')->references('ID_cliente')->on('cliente')->onDelete('set null');

            // Agregar campo descripcion
            $table->text('descripcion')->nullable()->after('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fletes', function (Blueprint $table) {
            // Eliminar foreign key y columna cliente_id
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');

            // Eliminar columna descripcion
            $table->dropColumn('descripcion');
        });
    }
};
