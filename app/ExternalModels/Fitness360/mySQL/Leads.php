<?php

namespace App\ExternalModels\Fitness360\mySQL;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    use SoftDeletes, UuidModel, CrudTrait;

    protected $table = 'leads';
    //protected $primaryKey = 'id';
    protected $guarded = ['id', 'uuid'];
    protected $connection = 'f3mysql';

    public function insert($data)
    {
        $results = false;

        $model = new $this;
        $model->gender   = $data['gender'];
        $model->first_name   = $data['first_name'];
        $model->last_name    = $data['last_name'];
        $model->full_name    = $data['full_name'];
        $model->email        = $data['email'];
        $model->mobile_phone = $data['mobile_phone'];

        $model->plan_info = json_encode($data['plan_info']);

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }
}
