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
            if (!Schema::hasColumn('confeccion', 'ID_cliente')) {
                // Assuming ID_confeccion is the first column, we try to put it after
                $table->unsignedBigInteger('ID_cliente')->after('ID_confeccion');

                // Add foreign key if possible, or just the column
                // Checking if cliente table ID is compatible
                // $table->foreign('ID_cliente')->references('ID_cliente')->on('cliente'); 
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confeccion', function (Blueprint $table) {
            if (Schema::hasColumn('confeccion', 'ID_cliente')) {
                $table->dropColumn('ID_cliente');
            }
        });
    }
};
