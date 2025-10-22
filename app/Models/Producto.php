<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'categoria_id', 'cantidad', 'precio_unitario'];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con movimientos
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
}
