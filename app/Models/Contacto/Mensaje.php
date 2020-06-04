<?php

namespace App\Models\Contacto;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    public function user() {
        return $this->belongsTo("App\User");
    }
}
