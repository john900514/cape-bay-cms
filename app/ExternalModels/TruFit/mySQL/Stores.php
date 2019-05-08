<?php

namespace App\ExternalModels\TruFit\mySQL;

use App\Traits\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stores extends Model
{
    use SoftDeletes, UuidModel;

    protected $table = 'stores';
    protected $primaryKey = 'id';
    protected $connection = 'tfmysql';

    protected $fillable = [
        'Address1',
        'Address2',
        'ClubId',
        'ClubName',
        'CorpId',
        'CorpName',
        'EndTime',
        'LocationHours',
        'RegionCode',
        'RegionName',
        'StartTime',
        'State',
        'ZipCode',
    ];

    public function createOrUpdateStore($data, $client_id)
    {
        $results = false;

        $model = $this->where('ClubId', '=', $data['ClubId'])
            ->where('client_id', '=', $client_id)
            ->first();

        if(is_null($model))
        {
            $model = new $this($data);
            $model->client_id = $client_id;
        }
        else
        {
            foreach($data as $col => $val)
            {
                if($col == 'Image')
                {
                    continue;
                }
                else
                {
                    $model->$col = $val;
                }
            }
        }

        if($model->save())
        {
            $results = $model;
        }

        return $results;
    }

    public function getDataRecord($clubId, $active = 1)
    {
        $results = false;

        $record = $this->where('ClubId', '=', $clubId)
            ->where('active', '=', $active)
            ->first();

        if($record)
        {
            $results = $record;
        }

        return $results;
    }

    public function getAllViaRegion($region, $active = 1)
    {
        $results = false;

        $record = $this->where('RegionCode', '=', $region)
            ->where('active', '=', $active)
            ->get();

        if(count($record) > 0)
        {
            $results = $record;
        }

        return $results;
    }

    public function sortViaCity($data)
    {
        $results = [];

        foreach ($data as $idx => $loc)
        {
            $results[$loc['Group']][] = $loc;
        }

        return $results;
    }

    public function location_in_mobile_app()
    {
        return $this->hasOne('App\ExternalModels\TruFit\pgSQL\Locations', 'phone_number', 'phone');
    }
}
