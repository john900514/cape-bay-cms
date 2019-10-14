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
}
