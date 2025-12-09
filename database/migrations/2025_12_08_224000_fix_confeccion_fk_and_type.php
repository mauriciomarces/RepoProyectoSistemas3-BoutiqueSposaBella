<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0. Clean up potential bad data that would block FK
        DB::table('confeccion')->update(['ID_transaccion' => null]);

        // 1. Ensure the column type matches the referenced column (BIGINT UNSIGNED)
        DB::statement('ALTER TABLE confeccion MODIFY ID_transaccion BIGINT UNSIGNED NULL');

        // 2. Add the foreign key constraint
        Schema::table('confeccion', function (Blueprint $table) {
            $table->foreign('ID_transaccion', 'fk_confeccion_movimiento_v2')
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
            $table->dropForeign('fk_confeccion_movimiento_v2');
        });
    }
};
