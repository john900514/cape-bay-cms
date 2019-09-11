<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedRoles extends Model
{
    public function role()
    {
        return $this->hasOne('App\Roles', 'id', 'role_id');
    }
}
