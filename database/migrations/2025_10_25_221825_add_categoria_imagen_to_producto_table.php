<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->string('categoria')->nullable()->after('descripcion'); // Casual, Fiesta, Gala, Novia, Trabajo
            $table->string('imagen')->nullable()->after('categoria'); // Ruta relativa a public/images/productos/
        });
    }

    public function down()
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'imagen']);
        });
    }

};
