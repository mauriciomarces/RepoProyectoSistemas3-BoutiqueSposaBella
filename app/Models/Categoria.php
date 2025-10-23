<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre'];

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
