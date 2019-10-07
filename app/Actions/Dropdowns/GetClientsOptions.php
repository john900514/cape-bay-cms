<?php

namespace App\Actions\Dropdowns;

use App\Clients;

class GetClientsOptions
{
    protected $clients;
    public function __construct(Clients $clients)
    {
        $this->clients = $clients;
    }

    public function execute(string $context)
    {
        $results = [];

        if($context == 'push-notes')
        {
            // Get All Clients who have push-note features
            $records = $this->clients->select('clients.*')
                ->join('client_features', 'client_features.client_id', '=', 'clients.id')
                ->where('client_features.page', '=', $context)
                ->where('clients.active', '=', 1)
                ->groupBy('clients.id')
                ->get();

            if(count($records) > 0)
            {
                // Return 2 arrays one with the names and one with the ids
                $results = [
                    'select' => [],
                    'link' => []
                ];
                foreach ($records as $idx => $client)
                {
                    $results['select'][] = [
                        'value' => $idx,
                        'text' => $client->name

                    ];
                    $results['link'][] = $client->id;
                }
            }
        }

        return $results;
    }
}
