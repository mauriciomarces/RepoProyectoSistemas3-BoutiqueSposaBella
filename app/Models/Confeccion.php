<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confeccion extends Model
{
    protected $table = 'confeccion';

    protected $primaryKey = 'ID_confeccion';

    protected $fillable = [
        'ID_cliente',
        'tipo_confeccion',
        'fecha_inicio',
        'fecha_entrega',
        'costo',
        'estado',
        'medidas',
        'ID_transaccion'
    ];

    protected $casts = [
        'medidas' => 'array',
        'fecha_inicio' => 'date',
        'fecha_entrega' => 'date',
        'costo' => 'decimal:2'
    ];

    public $timestamps = false; // Assuming no timestamps as per previous table structure observation, but will verify

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'ID_cliente', 'ID_cliente');
    }

    public function movimientoFinanciero()
    {
        return $this->belongsTo(MovimientoFinanciero::class, 'ID_transaccion', 'id');
    }
}
