<?php

namespace App\ExternalModels\Morning\mySQL;

use App\Traits\UuidModel;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads extends Model
{
    use CrudTrait, LogsActivity, SoftDeletes, UuidModel;

    protected $table = 'leads';
    //protected $primaryKey = 'id';
    protected $guarded = ['id', 'uuid'];
    protected $connection = 'mfmysql';


    protected static $logAttributes = ['first_name', 'last_name', 'email', 'mobile_phone', 'paramount_club_id'];

    public function insert($data)
    {
        $results = false;

        $model = new $this;
        $model->first_name   = $data['memberInfo']['firstName'];
        $model->last_name    = $data['memberInfo']['lastName'];
        $model->email        = $data['memberInfo']['email'];
        $model->mobile_phone = $data['memberInfo']['mobile'];

        $model->plan_info = json_encode($data['planInfo']);
        $model->paramount_club_id = $data['clubID'];

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function getRecordViaEmail($email)
    {
        $results = false;

        $record = $this->where('email', '=', $email)->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function getRecordViaData($email,$last_name, $phone, $club_id)
    {
        $results = false;

        $record = $this->where('email', '=', $email)
            ->where('last_name', '=', $last_name)
            ->where('mobile_phone', '=', $phone)
            ->where('paramount_club_id', '=', $club_id)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function getAllSeeminglyAbandonedLeads()
    {
        $results = false;

        $record = $this->select(DB::raw('leads.*'))
            ->where('leads.abandoned', '=', 0)
            ->leftJoin('member_conversions', 'member_conversions.lead_id', '=', 'leads.id')
            ->whereNull('member_conversions.id')
            ->where('leads.created_at', '<', DB::raw('(NOW() - INTERVAL 20 MINUTE)'))
            ->get();

        if(count($record) > 0)
        {
            $results = $record;
        }

        return $results;
    }
}
