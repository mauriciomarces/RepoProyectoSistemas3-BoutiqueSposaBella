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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_compra');
            $table->decimal('total_compra', 10, 2);
            $table->string('concepto')->nullable();
            $table->enum('estado', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->integer('ID_proveedor')->nullable();
            $table->integer('ID_empleado')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('fecha_compra');
            $table->foreign('ID_proveedor')->references('ID_proveedor')->on('proveedor')->onDelete('set null');
            $table->foreign('ID_empleado')->references('ID_empleado')->on('empleado')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
