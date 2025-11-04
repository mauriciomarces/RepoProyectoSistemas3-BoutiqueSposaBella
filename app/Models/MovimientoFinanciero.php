<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoFinanciero extends Model
{
    protected $table = 'movimiento_financiero';

    protected $fillable = [
        'tipo', 'monto', 'concepto', 'descripcion', 'fecha', 'categoria', 'referencia', 'ID_empleado'
    ];

    protected $dates = ['fecha'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'ID_empleado', 'ID_empleado');
    }
}
