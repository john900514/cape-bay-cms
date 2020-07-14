<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'title'];
}
