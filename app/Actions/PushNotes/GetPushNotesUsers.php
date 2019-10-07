<?php

namespace App\Actions\PushNotes;

use App\Services\PushNotificationsService;

class GetPushNotesUsers
{
    protected $service;

    public function __construct(PushNotificationsService $service)
    {
        $this->service = $service;
    }

    public function execute(int $client_id, int $feature_id)
    {
        $results = [];

        // Get the WalletPassService or the MobileAppService depending on the type
        $pn_svc = $this->service->getService($client_id, $feature_id);

        if($pn_svc)
        {
            // Call the Service's get available users
            $users = $pn_svc->getUsers($client_id);

            if($users && (count($users) > 0))
            {
                $results = $users;
            }

        }

        return $results;
    }
}
