<?php

namespace App\Repositories;

use App\Services\Clients\TheAtheticClubClientService;
use App\Services\Clients\TruFitClientService;

class ClientServiceRepository
{
    protected $trufit, $tac;
    public function __construct(TruFitClientService $trufit,
                                TheAtheticClubClientService $tac)
    {
        $this->trufit = $trufit;
        $this->tac = $tac;
    }

    public function getService($service_name)
    {
        switch($service_name)
        {
            case 'TruFitClientService':
                $results = $this->trufit;
                break;

            case 'TheAtheticClubClientService':
                $results = $this->tac;
                break;
            default:
                $results = false;
        }

        return $results;
    }

    public function getClubsModel($client_id)
    {
        switch($client_id)
        {
            case 2:
                $results = '\App\Models\TruFit\Stores';
                break;

            case 7:
                $results = '\App\Models\TAC\Stores';
                break;
            default:

                $results = false;
        }

        return $results;
    }

    public function getEnrollmentCrudModel($client_id)
    {
        switch($client_id)
        {
            case 2:
                $results = '\App\Models\TruFit\Conversions';
                break;

            case 7:
                $results = '\App\Models\TAC\Transactions';
                break;
            default:
                $results = false;
        }

        return $results;
    }

    public function getAmenitiesCrudModel($client_id)
    {
        switch($client_id)
        {
            case 2:
                $results = '\App\Models\TruFit\PromoAmenities';
                break;

            default:
                $results = false;
        }

        return $results;
    }

    public function getEnrollmentFieldDefs($client_id)
    {
        switch($client_id)
        {
            case 2:
                $results = $this->trufit->getEnrollmentCrudFields();
                break;

            case 7:
            default:
                $results = [];
        }

        return $results;
    }

    public function getClubName($client_id, $club_id)
    {
        $results = '';

        switch($client_id)
        {
            case 2:
            case 7:
                // Obtain the proper stores model.
                $model_name = $this->getClubsModel($client_id);
                $model = new $model_name();

                //Query for the ClubName with the given club_id
                $club = $model->where('ClubId', '=', $club_id)->first();

                if(!is_null($club))
                {
                    $results = $club->ClubName;
                }
                break;

            default:
                $results = false;
        }

        return $results;
    }

    public function getClubDropdown($client_id)
    {
        $results = [];

        switch($client_id)
        {
            case 2:
            case 7:
                // Obtain the proper stores model.
                $model_name = $this->getClubsModel($client_id);
                $model = new $model_name();

                //Query for the ClubName with the given club_id
                $clubs = $model->all();

                if(count($clubs) > 0)
                {
                    $results = ['Select a Club'];

                    foreach($clubs as $club)
                    {
                        $results[$club->ClubId] = $club->ClubName;
                    }
                }
                break;

            default:
                $results = false;
        }

        return $results;
    }
}
