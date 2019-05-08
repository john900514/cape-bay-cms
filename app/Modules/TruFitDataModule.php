<?php

namespace App\Modules;

use App\ExternalModels\TruFit\mySQL\Stores;
use App\ExternalModels\TruFit\pgSQL\Locations;

class TruFitDataModule {

    protected $client_id = '43d798ee-3247-4749-90a4-346b41d3e745';

    public function __construct()
    {

    }

    public function getModuleRepos()
    {
        // @todo - be sure to hardcode this
        return [
            [
                'name' => 'Clubs',
                'url' => "records/{$this->client_id}/clubs"
            ]
        ];
    }

    public function getModuleRepo($repoName)
    {
        switch($repoName)
        {
            case 'clubs':
                $stores_model = Stores::with('location_in_mobile_app')->get();

                if(count($stores_model) > 0)
                {
                    $results = ['' => 'Select a Club'];
                    foreach($stores_model->toArray() as $club)
                    {
                        $results[$club['ClubId']] = $club['ClubName'];
                    }

                }
                break;

            default:
                $results = false;
        }

        return $results;
    }
}
