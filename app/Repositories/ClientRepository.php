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
}
