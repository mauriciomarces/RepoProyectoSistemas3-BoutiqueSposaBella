<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroInteraccion extends Model
{
    use HasFactory;

    protected $table = 'registros_interaccion';

    protected $fillable = [
        'empleado_id',
        'accion',
        'modulo',
        'registro_id',
        'descripcion',
        'datos_anteriores',
        'datos_nuevos',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
