<?php

namespace App\Repositories;

use App\Clients;

class ClientRepository
{
    protected $clients;

    public function __construct(Clients $model)
    {
        $this->clients = new BaseRepository($model);
    }

    public function getAllTheClients()
    {
        return $this->clients->all();
    }

    public function getClientviaUUID($uuid)
    {
        $results = false;

        $record = $this->clients->getModel()
            ->where('uuid','=', $uuid)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }
}
