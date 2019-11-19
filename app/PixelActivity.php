<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PixelActivity extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes;

    protected $table = 'pixel_activity';
}
