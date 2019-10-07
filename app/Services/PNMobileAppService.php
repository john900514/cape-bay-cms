<?php

namespace App\Services;

use App\Services\Clients\TruFitClientService;

class PNMobileAppService
{
    public $trufit;

    public function __construct(TruFitClientService $trufit)
    {
        $this->trufit = $trufit;
    }

    public function getUsers(int $client_id)
    {
        $results = false;

        switch($client_id)
        {
            case 2:
                $results = $this->trufit->getMobileUsers();
                break;
        }

        return $results;
    }
}
