<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosInteraccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros_interaccion', function (Blueprint $table) {
            $table->id();

            // The empleado (employee) who performed the action
            $table->foreignId('empleado_id')->constrained('empleado')->onDelete('cascade');

            // The type of action executed, e.g. login, venta, create, edit, delete, etc.
            $table->string('accion')->index();

            // The module affected by the action, e.g. ventas, clientes, productos, etc.
            $table->string('modulo')->index();

            // The ID of the record affected in the module (nullable if not applicable)
            $table->unsignedBigInteger('registro_id')->nullable();

            // Detailed description of the action performed
            $table->text('descripcion');

            // JSON/text snapshot of the old data before change (nullable)
            $table->json('datos_anteriores')->nullable();

            // JSON/text snapshot of the new data after change (nullable)
            $table->json('datos_nuevos')->nullable();

            $table->timestamps();

            // Indexing for faster filtering by empleado, module, and action
            $table->index(['empleado_id', 'modulo', 'accion']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros_interaccion');
    }
}
