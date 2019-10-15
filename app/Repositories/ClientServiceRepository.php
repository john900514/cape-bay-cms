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
}
