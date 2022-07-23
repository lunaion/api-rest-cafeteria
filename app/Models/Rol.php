<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    // Indicamos la tabla - Conexión
    protected $table = 'rols';

    // Relación de uno a muchos de la tabla rols con la tabla users.
    public function users() {
        return $this->hasMany('App\Models\User');
    }
}
