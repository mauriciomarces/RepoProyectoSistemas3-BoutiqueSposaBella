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
        Schema::create('movimiento_financiero', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->decimal('monto', 10, 2);
            $table->string('concepto');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->string('categoria');  // Por ejemplo: ventas, confecciones, gastos_operativos, etc.
            $table->string('referencia')->nullable();  // ID de transacción, confección o pedido relacionado
            $table->integer('ID_empleado')->nullable();
            $table->foreign('ID_empleado')->references('ID_empleado')->on('empleado')->onDelete('set null');
            $table->timestamps();
            
            // Índices para mejorar el rendimiento de las consultas
            $table->index('fecha');
            $table->index('categoria');
            $table->index(['tipo', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_financiero');
    }
};
