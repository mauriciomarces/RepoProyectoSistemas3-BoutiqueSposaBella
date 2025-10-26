<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Indicar explícitamente el nombre de la tabla
    protected $table = 'producto';

    protected $fillable = ['nombre', 'descripcion', 'stock', 'ID_proveedor'];

    // Relación con movimientos (si existe)
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

}
