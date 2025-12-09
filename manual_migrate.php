<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "Starting Manual Migration...\n";

try {
    echo "Cleaning up invalid dates...\n";
    // Allow invalid dates temporarily to fix them
    DB::statement("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
    DB::statement("UPDATE confeccion SET fecha_entrega = NULL WHERE CAST(fecha_entrega AS CHAR) = '0000-00-00'");
    DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'"); // Restore strict (approximate)

    echo "Updating data...\n";
    DB::table('confeccion')->update(['ID_transaccion' => null]);

    echo "Modifying column...\n";
    DB::statement('ALTER TABLE confeccion MODIFY ID_transaccion BIGINT UNSIGNED NULL');

    echo "Adding FK...\n";
    Schema::table('confeccion', function (Blueprint $table) {
        $table->foreign('ID_transaccion', 'fk_confeccion_manual_debug')
            ->references('id')
            ->on('movimiento_financiero')
            ->onDelete('set null');
    });

    echo "SUCCESS!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}
