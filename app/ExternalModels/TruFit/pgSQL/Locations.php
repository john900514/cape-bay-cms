<?php

namespace App\ExternalModels\TruFit\pgSQL;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $table = 'locations';
    protected $connection = 'tfpgsql';
}
