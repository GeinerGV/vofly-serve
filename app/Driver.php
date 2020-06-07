<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    
    public const DNI_BASIC_VALIDATE_RULES = ["digits:8"];
}
