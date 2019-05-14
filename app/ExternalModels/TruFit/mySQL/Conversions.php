<?php

namespace App\ExternalModels\TruFit\mySQL;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversions extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $table = "member_conversions";
    protected $primaryKey = 'id';
    protected $connection = 'tfmysql';

    protected static $logAttributes = ['lead_id', 'ContractNumber', 'response'];

    public function insertNew($leads_id, $c_num, $data)
    {
        $results = false;

        $model = new $this;
        $model->lead_id         = $leads_id;
        $model->ContractNumber  = $c_num;
        $model->response        = json_encode($data);

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function lead()
    {
        return $this->hasOne('App\ExternalModels\TruFit\mySQL\Leads', 'id', 'lead_id');
    }
}