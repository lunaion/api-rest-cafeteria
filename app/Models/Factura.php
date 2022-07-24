<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model {

    protected $fillable = ['id_user'];

    // Relación de uno a muchos inversa (muchos a uno)
    public function user() {
        return $this->belongsTo('App\Models\User', 'id');
    } 

    public function detalle() {
        return $this->hasMany('App\Models\Detalle');
    }
}
