<?php

namespace App\Repositories;

use App\ClientFeatures;

class ClientFeaturesRepo
{
    protected $features;

    public function __construct(ClientFeatures $model)
    {
        $this->features = new BaseRepository($model);
    }

    public function getClientFeatures($client_id, $page)
    {
        $results = [];

        $records = $this->features->getModel()
            ->where('active', '=', 1)
            ->where('page', '=', $page)
            ->where('client_id','=', $client_id)
            ->get();

        if(count($records) > 0)
        {
            $results = $records->toArray();
        }
        return $results;
    }
}
