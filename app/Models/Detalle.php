<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $fillable = ['id_factura','id_producto','cantidad','precio'];

    // Relación de uno a muchos inversa (muchos a uno)
    public function producto() {
        return $this->belongsTo('App\Models\Producto', 'id');
    } 

    public function factura() {
        return $this->belongsTo('App\Models\Factura', 'id');
    } 
}
