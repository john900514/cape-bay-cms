<?php

namespace App;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AdMarkets extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes,UuidModel;
}
