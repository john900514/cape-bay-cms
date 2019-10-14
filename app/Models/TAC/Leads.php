<?php

namespace App\Models\TAC;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    use LogsActivity, SoftDeletes, UuidModel;

    protected $connection = 'tac-main-api-mysql';

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

    public function getPhoneLead($phone, $type = null) {

        $results = false;

        $setup = $this;

        if(!is_null($type))
        {
            $setup = $setup->where('type', '=', $type);
        }

        $record = $setup->where('mobile', '=', $phone)->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function getEmailLead($email, $type = null)
    {
        $results = false;

        $setup = $this;

        if(!is_null($type))
        {
            $setup = $setup->where('type', '=', $type);
        }

        $record = $setup->where('email', '=', $email)->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function getNameLead($fname, $lname, $type = null)
    {
        $results = false;

        $setup = $this;

        if(!is_null($type))
        {
            $setup = $setup->where('type', '=', $type);
        }

        $record = $setup
            ->where('fname', '=', $fname)
            ->where('lname', '=', $lname)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
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

    public function customer()
    {
        return $this->hasOne('App\Customers', 'lead_id', 'uuid');
    }

    public function club()
    {
        return $this->hasOne('App\Stores', 'id', 'store_id');
    }
}
