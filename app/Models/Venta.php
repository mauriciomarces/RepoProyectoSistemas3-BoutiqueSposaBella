<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $fillable = [
        'fecha_venta',
        'total_venta',
        'concepto',
        'estado',
        'ID_cliente',
        'ID_empleado'
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'total_venta' => 'decimal:2'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'ID_cliente', 'ID_cliente');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ID_empleado', 'ID_empleado');
    }
}