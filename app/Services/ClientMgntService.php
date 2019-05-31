<?php

namespace App\Services;

use Bouncer;
use App\Clients;
use App\Modules\TruFitDataModule;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ClientRepository;
use App\Repositories\ClientFeaturesRepo;

class ClientMgntService
{
    protected $clients_repo, $features_repo;

    public function __construct(ClientRepository $clients_repo, ClientFeaturesRepo $features_repo)
    {
        $this->clients_repo = $clients_repo;
        $this->features_repo = $features_repo;
    }

    public function getAllClients()
    {
        $results = [];

        // get all client records
        $records = $this->clients_repo->getAllTheClients();
        //$user = Auth::user();
        $user = backpack_user();

        if(count($records) > 0 && $user)
        {

            // @todo - make sure the user can 'view' client records
            foreach ($records as $record)
            {
                if($user->can('view', $record))
                {
                    $results[] = $record->toArray();
                }
            }
        }

        return $results;
    }

    public function getAllFeaturesForClient($page, $client_id)
    {
        return $this->features_repo->getClientFeatures($client_id, $page);
    }

    public function getClient($uuid)
    {
        return $this->clients_repo->getClientviaUUID($uuid);
    }

    public function getClientDataModule($client_uuid, Clients $client = null)
    {
        $results = false;

        if($client)
        {
            if($client_uuid != $client->uuid)
            {
                //@todo - get the proper client model;
                $client = $client->where('uuid', '=', $client_uuid)->first();

                if(is_null($client))
                {
                    return $results;
                }
            }

            $data_module = $client->data_module()->first();

            $results = new $data_module->module();
        }

        return $results;
    }
}
