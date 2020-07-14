<?php

namespace AnchorCMS;

use AnchorCMS\Clients;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;

class MenuOptions extends Model
{
    use Uuid;

    public function getOptions($type, string $role = '')
    {
        $results = [];

        $records = $this->whereType($type);

        if(!empty($role))
        {
            $records = $records->wherePermittedRole($role);
        }

        if(count($records = $records->get()) > 0)
        {
            $results = $records;
        }

        return $results;
    }
}
