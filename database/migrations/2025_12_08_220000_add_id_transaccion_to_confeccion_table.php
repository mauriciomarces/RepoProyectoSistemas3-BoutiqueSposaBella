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
        if (Schema::hasTable('confeccion')) {
            Schema::table('confeccion', function (Blueprint $table) {
                if (!Schema::hasColumn('confeccion', 'ID_transaccion')) {
                    $table->unsignedBigInteger('ID_transaccion')->nullable()->after('costo');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('confeccion')) {
            Schema::table('confeccion', function (Blueprint $table) {
                if (Schema::hasColumn('confeccion', 'ID_transaccion')) {
                    $table->dropColumn('ID_transaccion');
                }
            });
        }
    }
};
