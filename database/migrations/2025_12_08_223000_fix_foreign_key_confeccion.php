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
        Schema::table('confeccion', function (Blueprint $table) {
            // Drop the incorrect foreign key if it exists
            // We try multiple common naming conventions or the one from the error
            try {
                $table->dropForeign('confeccion_ibfk_1'); // MySQL default
            } catch (\Exception $e) {
                // Ignore if not found
            }

            try {
                $table->dropForeign(['ID_transaccion']); // Laravel default
            } catch (\Exception $e) {
                // Ignore
            }

            // Add the correct foreign key
            // Note: ID_transaccion in confeccion refers to id in movimiento_financiero
            $table->foreign('ID_transaccion')
                ->references('id')
                ->on('movimiento_financiero')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confeccion', function (Blueprint $table) {
            $table->dropForeign(['ID_transaccion']);
        });
    }
};
