<?php

namespace App;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Widgets extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    public static function getModel($name)
    {
        $results = false;

        $model = self::where('name', '=', $name)->first();

        if(!is_null($model))
        {
            $results = $model;
        }

        return $results;
    }
}
