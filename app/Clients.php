<?php

namespace AnchorCMS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Clients extends Model
{
    use Uuid, SoftDeletes;
}
