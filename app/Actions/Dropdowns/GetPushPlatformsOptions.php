<?php

namespace App\Actions\Dropdowns;

use App\Clients;

class GetPushPlatformsOptions
{
    protected $clients;
    public function __construct(Clients $clients)
    {
        $this->clients = $clients;
    }

    public function execute(string $client_id, string $page)
    {
        $results = [];

        // Get All Clients who have push-note features
        $records = $this->clients->select('client_features.*')
            ->join('client_features', 'client_features.client_id', '=', 'clients.id')
            ->where('client_features.page', '=', $page)
            ->where('clients.active', '=', 1)
            ->where('clients.id', '=', $client_id)
            ->get();

        if(count($records) > 0)
        {
            // Return 2 arrays one with the names and one with the ids
            $results = [
                'select' => [],
                'link' => []
            ];
            foreach ($records as $idx => $feature)
            {
                $results['select'][] = [
                    'value' => $idx,
                    'text' => $feature->feature

                ];
                $results['link'][] = $feature->id;
            }
        }

        return $results;
    }
}
