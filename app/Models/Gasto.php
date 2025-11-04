<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $table = 'gastos';
    protected $fillable = [
        'fecha_gasto',
        'monto',
        'concepto',
        'tipo_gasto',
        'ID_empleado'
    ];

    protected $casts = [
        'fecha_gasto' => 'date',
        'monto' => 'decimal:2'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ID_empleado', 'ID_empleado');
    }
}