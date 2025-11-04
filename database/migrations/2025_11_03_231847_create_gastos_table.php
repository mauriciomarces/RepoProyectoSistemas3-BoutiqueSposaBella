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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_gasto');
            $table->decimal('monto', 10, 2);
            $table->string('concepto')->nullable();
            $table->string('tipo_gasto')->default('Variable'); // 'Fijo' o 'Variable'
            $table->text('descripcion')->nullable();
            $table->integer('ID_empleado')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('fecha_gasto');
            $table->index('tipo_gasto');
            $table->foreign('ID_empleado')->references('ID_empleado')->on('empleado')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
