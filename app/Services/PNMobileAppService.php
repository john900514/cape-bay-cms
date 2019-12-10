<?php

namespace App\Services;

use App\Services\Clients\TheAtheticClubClientService;
use App\Services\Clients\TruFitClientService;

class PNMobileAppService
{
    public $tac, $trufit;

    public function __construct(TruFitClientService $trufit, TheAtheticClubClientService $tac)
    {
        $this->trufit = $trufit;
        $this->tac = $tac;
    }

    public function getUsers(int $client_id)
    {
        $results = false;

        switch($client_id)
        {
            case 2:
                $results = $this->trufit->getMobileUsers();
                break;

            case 7:
                $results = $this->tac->getMobileUsers();
                break;
        }

        return $results;
    }
}
