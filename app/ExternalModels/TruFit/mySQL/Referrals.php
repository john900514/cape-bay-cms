<?php

namespace App\ExternalModels\TruFit\mySQL;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referrals extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $table = 'referrals';
    protected $primaryKey = 'id';
    protected $connection = 'tfmysql';

    protected static $logAttributes = ['uuid','referral_name', 'first_name', 'last_name', 'email', 'mobile', 'club'];

    public function insert($data)
    {
        $results = false;

        $model = new $this;
        $model->first_name   = $data['first_name'];
        $model->last_name    = $data['last_name'];
        $model->email        = $data['email'];
        $model->mobile = $data['mobile'];

        $model->club = $data['club'];
        $model->referral_name = $data['referring_name'];

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }
}
