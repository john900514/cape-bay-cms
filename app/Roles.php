<?php

namespace AnchorCMS;

use AnchorCMS\Permissions;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'title'];

    public function getAssignedAbilities($role)
    {
        $results = false;

        $record = $this->whereName($role)->first();

        if(!is_null($record))
        {
            $permissions = Permissions::whereEntityType('roles')
                ->whereEntityId($record->id)
                ->get();

            $results = [];
            if(count($permissions) > 0)
            {
                foreach ($permissions as $perm)
                {
                    $ability = $perm->ability()->first();

                    if(!is_null($ability))
                    {
                        $results[] = $ability->toArray();
                    }
                }
            }
        }

        return $results;
    }
}
