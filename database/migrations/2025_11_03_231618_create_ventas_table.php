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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_venta');
            $table->decimal('total_venta', 10, 2);
            $table->string('concepto')->nullable();
            $table->enum('estado', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->integer('ID_cliente')->nullable();
            $table->integer('ID_empleado')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('fecha_venta');
            $table->foreign('ID_cliente')->references('ID_cliente')->on('cliente')->onDelete('set null');
            $table->foreign('ID_empleado')->references('ID_empleado')->on('empleado')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
