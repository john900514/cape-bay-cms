<?php

namespace App\ExternalModels\THE\mySQL;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $connection = 'tac-mysql';

    public function createNewLead($data, $type = null)
    {
        $results = false;

        $model = new $this();
        $model->fname = $data['fname'];
        $model->lname = $data['lname'];
        $model->email = $data['email'];
        $model->mobile = $data['mobile'];
        $model->consent = $data['consent'];
        $model->reason = $data['reason'];
        $model->store_id = $data['club_id'];
        $model->type = $type;

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function getLeads($type = null)
    {
        $results = false;

        $setup = $this;

        if(!is_null($type))
        {
            $setup = $setup->where('type', '=', $type);
        }

        $records = $setup->get();

        if(count($records) > 0)
        {
            $results = $records;
        }

        return $results;
    }

    public function getNewestLeads($type = null)
    {
        $results = false;

        $setup = $this->where('active', '=', 1)
            ->whereNull('date_reported');

        if(!is_null($type))
        {
            $setup = $setup->where('type', '=', $type);
        }

        $records = $setup->get();

        if(count($records) > 0)
        {
            $results = $records;
        }

        return $results;
    }

    public function getLatestLeads($type = null, $within = '-24 hours')
    {
        $results = false;

        $date = new \DateTime();
        $date->modify($within);
        $formatted_date = $date->format('Y-m-d H:i:s');

        $setup = $this->where('active', '=', 1)
            ->where('created_at', '>', $formatted_date);

        if(!is_null($type))
        {
            $setup = $setup->where('type', '=', $type);
        }

        $records = $setup->get();

        if(count($records) > 0)
        {
            $results = $records;
        }

        return $results;
    }
}
