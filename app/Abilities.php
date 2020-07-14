<?php

namespace AnchorCMS;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Abilities extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'title'];
}
