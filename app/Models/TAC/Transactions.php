<?php

namespace App\Models\TAC;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Transactions extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $connection = 'tac-main-api-mysql';

    public function createNewTransaction($customer_id, $type, $data)
    {
        $results = false;

        $model = new $this();
        $model->customer_id = $customer_id;
        $model->type = $type;
        $model->response = $data;

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }
}
