<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('fletes')) {
            Schema::create('fletes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('destinatario')->nullable();
                $table->string('direccion')->nullable();
                $table->string('telefono', 50)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('fletes');
    }
};
