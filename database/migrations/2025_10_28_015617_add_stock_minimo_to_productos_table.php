<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->integer('stock_minimo')->default(5)->after('stock');
        });
    }

    public function down()
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->dropColumn('stock_minimo');
        });
    }
};