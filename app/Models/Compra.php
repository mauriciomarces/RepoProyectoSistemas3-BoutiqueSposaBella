<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    protected $fillable = [
        'fecha_compra',
        'total_compra',
        'concepto',
        'estado',
        'ID_proveedor',
        'ID_empleado'
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'total_compra' => 'decimal:2'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'ID_proveedor', 'ID_proveedor');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ID_empleado', 'ID_empleado');
    }
}