<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = ['producto_id', 'tipo', 'cantidad', 'observacion'];

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
